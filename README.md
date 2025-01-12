# Laravel Blog API Project

A robust and scalable Blog API built with Laravel, designed to handle user authentication, blog post management, comments, image uploads, and error handling. The project follows a feature-driven development workflow, ensuring modular and organized development.

---

## Table of Contents

- [Project Overview](#project-overview)
- [Features](#features)
- [Development Workflow](#development-workflow)
- [Branches and Pull Requests](#branches-and-pull-requests)
- [Installation](#installation)
- [API Documentation](#api-documentation)
- [Testing](#testing)
- [Exception Handling](#exception-handling)
- [Thank You](#thank-you)

---

## Project Overview

This project demonstrates the implementation of a Laravel application for blogging. It includes features like user authentication, blog posts, comments, and token-based API authentication.

---

## Features

- **Authentication**: Secure user registration and login with token-based authentication.
- **Blog Posts**: Create, update, delete, and view blog posts.
- **Comments**: Add and manage comments on blog posts.
- **Image Upload**: Attach images to blog posts with validation.
- **Role Management**: Super admin management for enhanced control.
- **Exception Handling**: Comprehensive error handling for better debugging.
- **Testing**: PHPUnit tests for Post Comment validation.

---

## Development Workflow

### Branching Strategy

1. **Branches**:
   - `main`: Production-ready branch.
   - `develop`: Staging branch for integrating all features.
   - Feature branches derived from `develop` for each task.

2. **Feature Workflow**:
   - Each feature was implemented in its respective branch.
   - Upon completion, a Pull Request (PR) was made to merge changes into the `develop` branch.
   - Once all features were completed and reviewed, `develop` was merged into `main`.

---

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/bhupendra9211/blog-api.git
   cd blog-api
   ```

2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Configure the environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up the database:
   ```bash
   php artisan migrate --seed
   ```

5. Start the application:
   ```bash
   php artisan serve
   ```

---

## API Documentation

### Public Endpoints
#### User Authentication
1. **Register a User**
   - **POST** `/api/users/register`
   - **Payload**:
     ```json
     {
         "username": "testuser",
         "email": "testuser@example.com",
         "password": "password123",
         "password_confirmation": "password123"
     }
     ```
   - **Response**: User created with a success message.

2. **Login a User**
   - **POST** `/api/users/login`
   - **Payload**:
     ```json
     {
         "email": "testuser@example.com",
         "password": "password123"
     }
     ```
   - **Response**: User details with an authentication token.

---

### Protected Endpoints (Require Token)
#### Blog Posts
1. **Fetch All Posts**
   - **GET** `/api/posts`
   - **Response**: List of all posts with associated comments.

2. **Create a New Post**
   - **POST** `/api/posts`
   - **Payload**:
     ```json
     {
         "title": "My First Blog Post",
         "content": "This is the content of the blog post."
     }
     ```
   - **Response**: Details of the created post.

3. **Fetch a Single Post**
   - **GET** `/api/posts/{post}`
   - **Response**: Details of the specific post with comments.

4. **Update a Post**
   - **PUT** `/api/posts/{post}`
   - **Payload**:
     ```json
     {
         "title": "Updated Title",
         "content": "Updated content."
     }
     ```
   - **Response**: Updated post details.

5. **Delete a Post**
   - **DELETE** `/api/posts/{post}`
   - **Response**: Success message.

6. **Upload an Image to a Post**
   - **POST** `/api/posts/{post}/images`
   - **Payload**: Multipart form-data with the image file.
   - **Response**: Success message with updated post details.

7. **Delete an Image from a Post**
   - **DELETE** `/api/posts/{post}/images`
   - **Response**: Success message.

---

#### Comments
1. **Fetch All Comments for a Post**
   - **GET** `/api/posts/{post}/comments`
   - **Response**: List of all comments for the post.

2. **Add a Comment to a Post**
   - **POST** `/api/posts/{post}/comments`
   - **Payload**:
     ```json
     {
         "content": "This is a comment."
     }
     ```
   - **Response**: Details of the created comment.

3. **Update a Comment**
   - **PUT** `/api/comments/{comment}`
   - **Payload**:
     ```json
     {
         "content": "Updated comment content."
     }
     ```
   - **Response**: Updated comment details.

4. **Delete a Comment**
   - **DELETE** `/api/comments/{comment}`
   - **Response**: Success message.

---

## Testing

Run the tests using PHPUnit:
```bash
php artisan test
```

### Types of Tests

- **Feature Tests**: Test the end-to-end functionality of API routes and validation.

**Example Test Command**:
```bash
php artisan test
```

Evidence: All tests pass successfully.

---

## Exception Handling

### Purpose
Exception handling ensures:
- Errors are logged for debugging.
- Clients receive clear and user-friendly error messages.

### Implementation
- **Validation Errors**: Automatically handled and returned as structured JSON responses.

**Example Error Response**:
```json
{
    "status": false,
    "message": "Validation Error",
    "errors": {
        "title": ["The title field is required."]
    }
}
```

---

## Thank You 
### Contact Details
- Name : Bhupendra Kumar Sah
- Gmail : shahbhupendra@9211@gmail.com
- Number : 9801620807
- Portfolio : ```https://bhupendrasah.com.np/```

---
