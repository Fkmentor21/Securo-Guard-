import requests
from bs4 import BeautifulSoup
import pandas as pd
import re

# Step 1: Get the webpage content
# url = 'https://www.amazon.com/JBL-Quantum-100P-Console-Playstation/dp/B0BVDJJFXW/ref=pd_ci_mcx_pspc_dp_2_i_2' # Replace with the actual page URL
# response = requests.get(url)

# # # Step 2: Parse the HTML content with BeautifulSoup
# soup = BeautifulSoup(response.text, 'html.parser')

# # # Step 3: Find the product titles based on the specific HTML structure
# review = soup.find_all('div', class_='reviewText') 
# rating = soup.find_all('i', class_='review-rating')  # Adjust based on actual class

# # # # Step 4: Extract the text from the HTML elements and store them in a list
# reviews = [title.text.strip() for title in review]
# ratings= [title.text.strip() for title in rating]
# val =[]
# for text in ratings:
#     match = re.search(r'\d+(\.\d+)?', text)
#     if match:
#         val.append(float(match.group()))
#     else:
#         val.append(None)

# # print(len(reviews))
# # print(len(val))
# df = pd.DataFrame({'reviews': reviews, 'ratings': val})

# print(df)
def get_url(url):
    response = requests.get(url)

# # Step 2: Parse the HTML content with BeautifulSoup
    soup = BeautifulSoup(response.text, 'html.parser')

# # Step 3: Find the product titles based on the specific HTML structure
    review = soup.find_all('div', class_='reviewText') 
    rating = soup.find_all('i', class_='review-rating')  # Adjust based on actual class

# # # Step 4: Extract the text from the HTML elements and store them in a list
    reviews = [title.text.strip() for title in review]
    ratings= [title.text.strip() for title in rating]
    val =[]
    for text in ratings:
        match = re.search(r'\d+(\.\d+)?', text)
        if match:
            val.append(float(match.group()))
        else:
            val.append(None)

    # return reviews
    # print(reviews)

# print(len(reviews))
# print(len(val))
    df = pd.DataFrame({'reviews': reviews, 'ratings': val})
    return df
    # print(df)