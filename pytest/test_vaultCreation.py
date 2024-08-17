# Generated by Selenium IDE
import pytest
import time
import json
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support import expected_conditions
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.desired_capabilities import DesiredCapabilities

class TestVaultCreation():
  def setup_method(self, method):
    self.driver = webdriver.Remote(command_executor='http://localhost:4444/wd/hub', desired_capabilities=DesiredCapabilities.CHROME)
    self.vars = {}
  
  def teardown_method(self, method):
    self.driver.quit()
  
  def test_vaultCreation(self):
    self.driver.get("https://localhost/")
    self.driver.set_window_size(1680, 1025)
    self.driver.find_element(By.LINK_TEXT, "Vaults").click()
    self.driver.find_element(By.CSS_SELECTOR, ".mb-2").click()
    self.driver.find_element(By.ID, "vaultName").click()
    self.driver.find_element(By.ID, "vaultName").send_keys("Vault Created")
    self.driver.find_element(By.CSS_SELECTOR, "#addVaultForm > .btn").click()
    self.driver.find_element(By.CSS_SELECTOR, "tr:nth-child(2) > td:nth-child(1)").click()
    elements = self.driver.find_elements(By.CSS_SELECTOR, "tr:nth-child(2) > td:nth-child(1)")
    assert len(elements) > 0
  
