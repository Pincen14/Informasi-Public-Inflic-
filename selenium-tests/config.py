import os

BASE_URL = os.getenv("BASE_URL", "http://127.0.0.1:8000")
ADMIN_EMAIL = os.getenv("ADMIN_EMAIL", "admin@admin.com")
ADMIN_PASSWORD = os.getenv("ADMIN_PASSWORD", "password")
USER_EMAIL = os.getenv("USER_EMAIL", "user@student.com")
USER_PASSWORD = os.getenv("USER_PASSWORD", "password")
HEADLESS = os.getenv("HEADLESS", "true").lower() == "true"
BROWSER = os.getenv("BROWSER", "chrome").lower()

DB_HOST = os.getenv("DB_HOST", "127.0.0.1")
DB_PORT = int(os.getenv("DB_PORT", "3306"))
DB_DATABASE = os.getenv("DB_DATABASE", "Website_Informasi")
DB_USERNAME = os.getenv("DB_USERNAME", "root")
DB_PASSWORD = os.getenv("DB_PASSWORD", "")
