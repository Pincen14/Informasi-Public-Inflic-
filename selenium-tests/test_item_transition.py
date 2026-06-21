import pymysql

from selenium.webdriver.common.by import By
from selenium.webdriver.support import expected_conditions as EC

from base_test import BaseTest
from config import (
    ADMIN_EMAIL, ADMIN_PASSWORD, BASE_URL,
    DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD,
)


class TestItemStateTransition(BaseTest):
    """State transition Selenium: laporan pending -> approved oleh admin."""

    @staticmethod
    def prepare_pending_item():
        connection = pymysql.connect(
            host=DB_HOST, port=DB_PORT, database=DB_DATABASE,
            user=DB_USERNAME, password=DB_PASSWORD,
        )
        with connection:
            with connection.cursor() as cursor:
                cursor.execute(
                    "SELECT id FROM users WHERE email = %s", ("user@student.com",)
                )
                user_id = cursor.fetchone()[0]
                cursor.execute("DELETE FROM items WHERE nama_item = %s", ("Dompet Selenium",))
                cursor.execute(
                    """INSERT INTO items
                    (nama_item, description, image, location_found, date_found, time_found,
                     finder_name, finder_contact, status, user_id, created_at, updated_at)
                    VALUES (%s, %s, %s, %s, CURDATE(), %s, %s, %s, %s, %s, NOW(), NOW())""",
                    ("Dompet Selenium", "Data pengujian approval Selenium.", "items/placeholder.jpg",
                     "Lab Pengujian", "10:30", "User Inflic", "081234567891", "pending", user_id),
                )
            connection.commit()

    def test_01_admin_approves_pending_item(self):
        self.prepare_pending_item()
        self.driver.get(f"{BASE_URL}/login")
        self.wait.until(EC.visibility_of_element_located((By.NAME, "email"))).send_keys(ADMIN_EMAIL)
        self.driver.find_element(By.NAME, "password").send_keys(ADMIN_PASSWORD)
        self.driver.find_element(By.CSS_SELECTOR, "button[type='submit']").click()
        self.wait.until(EC.url_contains("/dashboard/admin"))

        row = self.wait.until(EC.presence_of_element_located((By.XPATH, "//tr[.//*[contains(text(), 'Dompet Selenium')]]")))
        self.assertIn("Pending", row.text)
        row.find_element(By.CSS_SELECTOR, "button[title='Approve']").click()
        self.driver.switch_to.alert.accept()
        self.wait.until(EC.text_to_be_present_in_element((By.XPATH, "//tr[.//*[contains(text(), 'Dompet Selenium')]]"), "Approved"))
