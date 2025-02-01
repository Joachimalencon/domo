# Domo
### Docker Monitoring App

---

## 🚀 API Setup

1. **Configuration**
    - You can either:
        - **Run the setup script**: Execute **`./setup_env.sh`** once to interactively generate the necessary configurations for both API and frontend.
        - **Or manually update the files** by following the instructions below. If you do not use the setup script, you must:

   **1. Manually generate the JWT keys:**
    ```bash
    sudo mkdir -p api/config/jwt
    sudo openssl genrsa -out api/config/jwt/private.pem 4096
    sudo openssl rsa -pubout -in api/config/jwt/private.pem -out api/config/jwt/public.pem
    ```

   **2. Copy the template environment files:**
    ```bash
    cp api/.env.template api/.env
    cp front/.env.production.template front/.env.production
    ```
   Then, update the copied files as needed for your configuration.

3. **Create a symbolic link**
    - Run the following command to create a symbolic link from the project root to the API's `.env` file:
    ```bash
    ln -s $(pwd)/api/.env .env
    ```
   This ensures that the environment variables are accessible throughout the project.

4. **CORS Configuration**
    - Set the following in **`/api/.env`**:
    ```env
    CORS_ALLOW_ORIGIN=Your_URL_or_server_IP
    ```

5. **Database Credentials**
    - The default credentials are defined in **`/api/.env`**, but it is **highly recommended** to change them for security purposes:
    ```env
    DB_USER=admin  
    DB_PASSWORD=zAKBae64cXh9ybCd  
    DB_URL=host.docker.internal:7070  
    DB_DATABASE=domo  
    ```

---

## 🌐 Frontend Setup

1. **Configuration**
    - You can either:
        - **Run the setup script**: Execute **`./setup_env.sh`** once to automatically generate both the **`/api/.env`** and **`/front/.env.production`** files.
        - **Or manually update the file** by following the instructions below.

2. **Manual Update**
    - Open the file: **`/front/.env.production`**
    - Set the API URL:
    ```env
    VITE_API_URL=http://your-ip-or-url:6969  
    ```

---

## ⚙️ Default Ports

| Service       | Port  |
|---------------|-------|
| **API**       | 6969  |
| **Database**  | 7070  |
| **Frontend**  | 7171  |

---

## 🛠️ Usage

**Start the Docker containers**:
```bash
docker compose up --build -d
```

---

## 🖍️ Additional Notes

- Ensure that the CORS variables (in **`/api/.env`**) and the API URL (in **`/front/.env.production`**) are correctly configured based on your production or development environment.
- Change the database credentials for a more secure production environment.

---

**Happy Monitoring with Domo!** 🎉