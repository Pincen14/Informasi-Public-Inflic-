import unittest

from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.support.ui import WebDriverWait
from webdriver_manager.chrome import ChromeDriverManager
from webdriver_manager.microsoft import EdgeChromiumDriverManager

from config import BROWSER, HEADLESS


class BaseTest(unittest.TestCase):
    def setUp(self):
        options = webdriver.EdgeOptions() if BROWSER == "edge" else webdriver.ChromeOptions()
        if HEADLESS:
            options.add_argument("--headless=new")
        options.add_argument("--window-size=1440,1000")
        options.add_argument("--no-sandbox")
        options.add_argument("--disable-dev-shm-usage")
        if BROWSER == "edge":
            self.driver = webdriver.Edge(
                service=Service(EdgeChromiumDriverManager().install()), options=options
            )
        else:
            self.driver = webdriver.Chrome(
                service=Service(ChromeDriverManager().install()), options=options
            )
        self.wait = WebDriverWait(self.driver, 10)

    def tearDown(self):
        self.driver.quit()
