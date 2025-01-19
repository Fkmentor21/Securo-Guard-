import requests
from bs4 import BeautifulSoup

# Step 1: Get the webpage content
url = 'https://priceoye.pk/wireless-earbuds/audionic/audionic-wireless-airbuds-425'  # Replace with the actual website URL you're working with
response = requests.get(url)

# Step 2: Parse the HTML content with BeautifulSoup
soup = BeautifulSoup(response.text, 'html.parser')

# Step 3: Extract and print the entire HTML structure
html_structure = soup.prettify()  # This formats the HTML with indentation for easier reading

# Step 4: Print the HTML structure to the console
print(html_structure)
