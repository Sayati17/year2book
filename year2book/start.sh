#!/bin/bash
set -e

# Remove existing containers and networks
docker stop mysql || true
docker stop year2book || true
docker rm mysql || true
docker rm year2book || true
docker network rm year2book-hub || true

# Go to mysql folder
cd mysql

# Build Docker image
docker build -t mysql-images .

# Run Docker container
docker run -d -p 3306:3306 --name mysql mysql-images

# Go back to the main folder
cd ../

# Build Docker image
docker build -t year2book-images .

# Run Docker container
docker run -d -p 8080:80 --name year2book year2book-images

# Create Network
docker network create year2book-hub

# Connect Network
docker network connect year2book-hub mysql
docker network connect year2book-hub year2book
