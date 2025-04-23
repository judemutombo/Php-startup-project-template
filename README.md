
# 🌐 Fullstack PHP Framework – API & SSR System

This project is a custom PHP framework designed to serve **both API endpoints** and **server-rendered pages** from the same architecture. It's built with modularity and performance in mind, featuring a compiler-based routing system for APIs and a traditional MVC-style rendering system for web pages.

## 🚀 Key Features

- 📦 **Dual-purpose Project**: Handles RESTful APIs and server-side rendered (SSR) views within the same project.
- 🧠 **Route Compilation System**: Inspired by compilers, this system pre-registers all route patterns and expected parameters for efficient runtime routing.
- ⚙️ **Custom Routing Engine**:
  - Supports RESTful method folders: `GET`, `POST`, `PUT`, etc.
  - Uses `RouteParameterValidator::set()` for defining dynamic route patterns.
  - Compiles routes into `compiled_routes.php` for high-performance matching.
- 🧪 **Parameter Validation**:
  - URL validation using `RouteParameterValidator` and `RequestParameter`.
  - Automatic parameter matching against defined route requirements.
- 🛠️ **Middleware Support**: Global or route-specific middleware for validation or security.
- 📄 **Simple SSR**: Pages and templates can be rendered directly using the `Views` folder and controllers.

## 📁 Folder Structure (Simplified)

```
App/
├── API/                      # Core API routing logic and compiled configurations
│   ├── Configuration/        # Router, Bootstrap, and Parameter Validators
│   ├── Methods/              # GET, POST, PUT HTTP method folders
│   ├── MiddleWare/           # Middleware system
├── Controller/               # Handles logic for SSR views
├── Views/                    # Server-rendered templates and pages
├── Sublime/                  # Route compilation tools
Public/                       # Public-facing assets (CSS, JS, images)
index.php                     # Main entry point
README.md
```

## 🔄 How the Routing System Works

1. **Defining an Endpoint**:
   Create a file like `GET/users.php`.
   Inside the file, use:
   ```php
   RouteParameterValidator::set("/:id/:name");
   ```
   This marks the file as expecting two parameters.

2. **Compiling the Routes**:
   Run the route compiler via `RouteRegistry` or `Compiler.php`. It:
   - Scans the API folders.
   - Extracts the route patterns from `RouteParameterValidator::set`.
   - Saves them into `compiled_routes.php`.

3. **Runtime Matching**:
   When a request like `GET /api/users/3/john` comes in:
   - The router first looks for a specific file like `/GET/users/3/john.php`.
   - If not found, it checks `compiled_routes.php` for a matching pattern like `users/:id/:name`.
   - If matched, the request is routed to `users.php` and parameters are passed accordingly.

4. **Fallback**:
   - If no match is found at any level, the system returns a `404 Not Found`.

## 🧠 Server-Side Rendering (SSR)

- Pages are served via files in `Views/Pages/`.
- Layout templates are placed in `Views/Templates/`.
- `Controller/` handles the business logic for these pages.

## 🧪 Example Endpoint

```php
// GET/hello.php
RouteParameterValidator::set("/:name");
echo "Hello, " . $_GET['name'];
```

## 💻 Local Development

1. Clone the repository.
2. Install dependencies:
   ```bash
   composer install
   ```
3. Start a PHP server:
   ```bash
   php -S localhost:8000 -t .
   ```
4. Access API via `http://localhost:8000/api/` or SSR pages via `http://localhost:8000/`.

## 🧩 Contribution

You're welcome to contribute by:
- Adding new modules to the API routing system
- Improving SSR structure
- Enhancing middleware or autoloading systems
- Implementing the SSR routing System
Please submit pull requests or open issues if you encounter bugs or have suggestions.

## 📄 License

MIT License

---

This framework is lightweight, fast, and ideal for small to medium PHP projects that require both API and SSR capabilities in one codebase.
