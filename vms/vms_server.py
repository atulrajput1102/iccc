import socket

def start_server(host='127.0.0.1', port=5500):
    server_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server_socket.bind((host, port))
    server_socket.listen(1)
    print(f"Server started at {host}:{port}")

    while True:
        client_socket, client_address = server_socket.accept()
        print(f"Connection from {client_address} has been established!")

        message = client_socket.recv(1024).decode('utf-8')
        print(f"Received message: {message}")

        # Simulate a response from the VMS board
        response = "Message received and processed"
        client_socket.send(response.encode('utf-8'))

        client_socket.close()

if __name__ == "__main__":
    start_server()
