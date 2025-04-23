# ⚡ Lightweight PHP Framework for API Services & Server-Side Rendering

This is a custom-built PHP framework designed to handle **RESTful API services** and **server-side rendered (SSR) pages** within a single project. It offers a clean, modular structure and provides performance-oriented tools like route compilation and middleware integration.

---

## ✨ Features

### 🔹 API Service System
- ✅ Folder-based routing (e.g., `GET/users.php`, `POST/login.php`)
- ✅ Support for route parameters using `RouteParameterValidator`
- ✅ Global and endpoint-specific middleware support
- ✅ Route compilation step for performance optimization
- ✅ Clean error handling and JSON responses

### 🔹 Server-Side Rendering (SSR)
- ✅ Dedicated rendering mechanism for HTML pages
- ✅ Ideal for SEO-friendly and fast-loading dynamic content
- ✅ Easy to integrate with any template engine or use pure PHP
- ✅ Simple routing for static and dynamic views

---

## 🧠 Architecture Overview

- Fully written in **pure PHP**
- Minimal dependencies, fast execution
- Custom router handles both static routes and dynamic parameterized endpoints
- Routes declared using `RouteParameterValidator::set('/:id/:name')`
- Compiled route map improves speed and prevents runtime traversal of every endpoint file

---

## 📁 Folder Structure

```
myapp/
├── api/
│   ├── GET/
│   │   ├── users.php
│   │   └── users.id.php
│   ├── POST/
│   │   └── login.php
│   └── MiddleWare/
│       └── Verificator.php
├── views/
│   └── home.php
├── system/
│   └── Router.php
│   └── RouteParameterValidator.php
├── setup.php
└── compile_routes.php
```

---

## 🛠 Usage

### 🗂 Registering Routes with Parameters
In your API endpoint file (e.g., `GET/users.php`), define expected parameters like this:

```php
use API\Router\RouteParameterValidator;

RouteParameterValidator::set('/:id/:name');
```

This allows matching requests like:
```
GET /api/users/4/Jude
```

### ⚡ Route Compilation (Performance Boost)
After creating or modifying endpoints, run the route compiler:

```bash
php compile_routes.php
```

This will:
- Scan all `GET/`, `POST/`, etc. endpoint files
- Extract expected parameters via `RouteParameterValidator`
- Build a precompiled router collection to speed up future requests

---

## 🧩 Middleware Support

Register middleware globally or per endpoint in your `setup.php`:

```php
use API\Router\Router;

Router::setGlobalMiddleWare("Verificator");
Router::setEndPointMiddleWare("users", "AuthCheck");

Router::serve();
```

Middleware classes must implement a static `serve()` method.

---

## 🚀 Contribution Guide

Want to contribute?

1. Fork this repository
2. Add new routes in `GET/`, `POST/`, etc.
3. Use `RouteParameterValidator::set()` if parameters are expected
4. Run the compiler (`php compile_routes.php`)
5. Create a pull request with a description of your changes

---

## 📄 License

This project is open-source and available under the MIT License.

---

## 💼 Add to Your CV

**Custom PHP Framework for API & SSR**
> Built a full-featured PHP framework supporting REST APIs and SSR views in the same application. Developed a dynamic route system with parameter validation, middleware support, and route compilation for high-performance execution. Inspired by Laravel and Express.js, the framework is lightweight, modular, and easy to extend.

---

## 🔗 Author

Created by [Your Name] — Contributions, questions, and feedback are welcome!
