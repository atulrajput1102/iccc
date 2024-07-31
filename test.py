import cv2
import pandas as pd
from ultralytics import YOLO
from tracker import Tracker
import cvzone
import numpy as np
import os
from datetime import datetime
import mysql.connector

# MySQL database configuration
db_connection = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="",
    database="iccc"
)
cursor = db_connection.cursor()

# Create table if not exists
cursor.execute("""
CREATE TABLE IF NOT EXISTS captured_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT,
    frame_filename VARCHAR(255),
    video_filename VARCHAR(255),
    capture_time DATETIME,
    UNIQUE KEY (vehicle_id, capture_time)
)
""")
db_connection.commit()

# Load YOLO model
model = YOLO('yolov8s.pt')

# Function to handle mouse events
def mouse_callback(event, x, y, flags, param):
    global frame
    if event == cv2.EVENT_LBUTTONDOWN:
        # Save the frame when the left mouse button is clicked
        if frame is not None:
            save_full_frame(frame)
            if out is not None:
                stop_video_writer()
                print("Video saved.")

cv2.namedWindow('RGB')
cv2.setMouseCallback('RGB', mouse_callback)

# Open video capture
cap = cv2.VideoCapture('wrongway.mp4')
if not cap.isOpened():
    raise IOError("Error opening video file.")

# Load class names
with open("coco.txt", "r") as my_file:
    class_list = my_file.read().split("\n")

# Initialize tracker and video writer
tracker = Tracker()
fourcc = cv2.VideoWriter_fourcc(*'XVID')
out = None
frame_count = 0

# Initialize dictionaries to track vehicle positions and timestamps
vehicle_positions = {}
vehicle_timestamps = {}
stationary_duration = 10
capture_duration = 20  # Duration for capturing video in seconds
capture_start_time = {}  # Track when video capture starts
video_captured = set()  # Track which vehicle IDs have had their video captured

def save_full_frame(frame, filename=None):
    if filename is None:
        current_datetime = datetime.now().strftime("%Y%m%d%H%M%S")
        filename = os.path.join("stationary", f"frame_{current_datetime}.jpg")
    cv2.imwrite(filename, frame)
    print(f"Saved image: {filename}")
    # Insert frame data into the database
    cursor.execute("INSERT INTO captured_data (frame_filename, capture_time) VALUES (%s, %s)", (filename, datetime.now()))
    db_connection.commit()

def start_video_writer(frame, vehicle_id):
    global out
    current_datetime = datetime.now().strftime("%Y%m%d%H%M%S")
    video_filename = os.path.join("stationary", f"stationary_{vehicle_id}_{current_datetime}.mp4")
    out = cv2.VideoWriter(video_filename, fourcc, 20.0, (frame.shape[1], frame.shape[0]))
    print(f"Started video writer: {video_filename}")
    # Update video filename in database for the current vehicle ID
    cursor.execute("INSERT INTO captured_data (vehicle_id, video_filename, capture_time) VALUES (%s, %s, %s) ON DUPLICATE KEY UPDATE video_filename = %s", (vehicle_id, video_filename, datetime.now(), video_filename))
    db_connection.commit()

def stop_video_writer():
    global out
    if out is not None:
        out.release()
        out = None
        print("Stopped video writer.")

def fast_forward_video(input_video_path, output_video_path, speed=5.0):
    cap = cv2.VideoCapture(input_video_path)
    fps = int(cap.get(cv2.CAP_PROP_FPS))
    delay = int(1000 / (fps * speed))  # Delay between frames for fast forward

    fourcc = cv2.VideoWriter_fourcc(*'XVID')
    out = cv2.VideoWriter(output_video_path, fourcc, fps / speed, (int(cap.get(3)), int(cap.get(4))))

    while True:
        ret, frame = cap.read()
        if not ret:
            break
        out.write(frame)

    cap.release()
    out.release()
    print(f"Fast forward video saved: {output_video_path}")

os.makedirs("stationary", exist_ok=True)

while True:
    ret, frame = cap.read()
    if not ret:
        break

    frame = cv2.resize(frame, (1020, 500))
    frame_count += 1

    # Predict objects in the frame
    results = model.predict(frame)
    a = results[0].boxes.data
    px = pd.DataFrame(a).astype("float")
    car_boxes = []

    # Filter car detections
    for index, row in px.iterrows():
        x1 = int(row[0])
        y1 = int(row[1])
        x2 = int(row[2])
        y2 = int(row[3])
        d = int(row[5])
        c = class_list[d]
        if 'car' in c:
            car_boxes.append([x1, y1, x2, y2])

    # Update tracker with car boxes
    bbox_idx = tracker.update(car_boxes)
    current_time = datetime.now()

    for bbox in bbox_idx:
        x3, y3, x4, y4, id = bbox
        cx = (x3 + x4) // 2
        cy = (y3 + y4) // 2

        # Track stationary vehicles
        if id in vehicle_positions:
            prev_x, prev_y = vehicle_positions[id]
            if abs(prev_x - cx) < 10 and abs(prev_y - cy) < 10:
                # Vehicle is stationary
                if id not in vehicle_timestamps:
                    vehicle_timestamps[id] = current_time
                    capture_start_time[id] = current_time
                else:
                    elapsed_time = (current_time - vehicle_timestamps[id]).total_seconds()
                    if elapsed_time > stationary_duration:
                        cvzone.putTextRect(frame, 'Stationary', (x3, y3 - 40), 1, 2, offset=10, colorR=(0, 255, 0))
                        if id not in video_captured:
                            # Start capturing video
                            start_video_writer(frame, id)
                            video_captured.add(id)  # Mark this vehicle's video as captured
                            capture_start_time[id] = current_time
                    if id in capture_start_time:
                        # Capture video segment
                        elapsed_video_time = (current_time - capture_start_time[id]).total_seconds()
                        if elapsed_video_time <= capture_duration and out is not None:
                            out.write(frame)
                        elif elapsed_video_time > capture_duration:
                            stop_video_writer()
                            capture_start_time.pop(id, None)  # Remove the entry after video capture
                            # Mark vehicle as needing to move before another video is captured
                            vehicle_positions.pop(id, None)
            else:
                # Vehicle has moved; reset tracking
                vehicle_positions[id] = (cx, cy)
                if id in vehicle_timestamps:
                    del vehicle_timestamps[id]
                if id in capture_start_time:
                    capture_start_time.pop(id, None)
                # Reset video captured flag when vehicle moves away
                if id in video_captured:
                    video_captured.remove(id)
        else:
            # Initialize vehicle position and timestamp
            vehicle_positions[id] = (cx, cy)

    # Display frame
    cv2.imshow("RGB", frame)

    if cv2.waitKey(1) & 0xFF == 27:  # Press 'Esc' to exit
        break

# Release resources
cap.release()
if out is not None:
    out.release()
cv2.destroyAllWindows()

# Play the most recent stationary video in fast forward mode
for video_file in sorted(os.listdir("stationary")):
    if video_file.endswith(".mp4"):
        video_path = os.path.join("stationary", video_file)
        output_path = os.path.join("stationary", f"fast_forwarded_{video_file}")
        print(f"Fast forwarding video: {video_path}")
        fast_forward_video(video_path, output_path, speed=5.0)
        break

# Close database connection
cursor.close()
db_connection.close()
