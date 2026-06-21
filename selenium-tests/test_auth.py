from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from base_test import BaseTest
from config import ADMIN_EMAIL, ADMIN_PASSWORD, BASE_URL, USER_EMAIL, USER_PASSWORD


class TestAuthentication(BaseTest):
    def login(self, email, password):
        self.driver.get(f"{BASE_URL}/login")
        self.wait.until(EC.visibility_of_element_located((By.NAME, "email"))).send_keys(email)
        self.driver.find_element(By.NAME, "password").send_keys(password)
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()

    def test_01_login_page_is_visible(self):
        self.driver.get(f"{BASE_URL}/login")
        self.assertIn("Welcome to Infogritas", self.driver.page_source)

    def test_02_user_login_goes_to_user_dashboard(self):
        self.login(USER_EMAIL, USER_PASSWORD)
        self.wait.until(EC.url_contains("/dashboard/user"))
        self.assertIn("Temukan Barang Kesayangan", self.driver.page_source)

    def test_03_admin_login_goes_to_admin_dashboard(self):
        self.login(ADMIN_EMAIL, ADMIN_PASSWORD)
        self.wait.until(EC.url_contains("/dashboard/admin"))
        self.assertIn("Dashboard Admin", self.driver.page_source)

    def test_04_invalid_password_stays_on_login(self):
        self.login(USER_EMAIL, "password-salah")
        self.wait.until(EC.presence_of_element_located((By.NAME, "email")))
        self.assertTrue(self.driver.current_url.endswith("/login"))

    def test_05_user_cannot_open_admin_dashboard(self):
        self.login(USER_EMAIL, USER_PASSWORD)
        self.driver.get(f"{BASE_URL}/dashboard/admin")
        self.assertNotIn("Dashboard Admin", self.driver.page_source)
