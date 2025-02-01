# Domo
### Docker Monitoring App

---

## 🚀 API Setup

1. **Configuration**
    - You can either:
        - **Run the setup script**: Execute **`./setup_env.sh`** once to interactively generate the necessary configurations for both API and frontend.
        - **Or manually update the files** by following the instructions below.

2. **Manual Update**
    - Open the file: **`/api/.env`**
    - Set the following values:

   ```env
   CORS_ALLOW_ORIGIN=Your_URL_or_server_IP
   ```

3. **Database credentials**
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

