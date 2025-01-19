# from flask import Flask, render_template
from flask import Flask, render_template, request, redirect
# import requests
# from bs4 import BeautifulSoup
# import pandas as pd
# import re
#import io
#import PyPDF2
#import firebase_admin
# from transformers import BertTokenizer, BertForSequenceClassification
# import torch
# import numpy as np
import test,model

# Load the trained model and tokenizer

#from firebase_admin import credentials
#from firebase_admin import db 
#import spacy
# from flask_sqlalchemy import SQLAlchemy
# from firebase import Firebase
# from datetime import datetime
#cred = credentials.Certificate('ner-67bde-firebase-adminsdk-v3dir-b62c828053.json')
#firebase_admin.initialize_app(cred, {
#    'databaseURL': 'https://ner-67bde-default-rtdb.firebaseio.com/'
#})

#db = firebase_admin.db.reference()
# def get_url(url):
#     response = requests.get(url)

# # # Step 2: Parse the HTML content with BeautifulSoup
#     soup = BeautifulSoup(response.text, 'html.parser')

# # # Step 3: Find the product titles based on the specific HTML structure
#     review = soup.find_all('div', class_='reviewText') 
#     rating = soup.find_all('i', class_='review-rating')  # Adjust based on actual class

# # # # Step 4: Extract the text from the HTML elements and store them in a list
#     reviews = [title.text.strip() for title in review]
#     ratings= [title.text.strip() for title in rating]
#     val =[]
#     for text in ratings:
#         match = re.search(r'\d+(\.\d+)?', text)
#         if match:
#             val.append(float(match.group()))
#         else:
#             val.append(None)

# # print(len(reviews))
# # print(len(val))
#     df = pd.DataFrame({'reviews': reviews, 'ratings': val})

#     print(df)


app = Flask(__name__)
# app.config['SQLALCHEMY_DATABASE_URI'] = "sqlite:///todo.db"
# app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
# db = SQLAlchemy(app)





# config = {
#   "apiKey": "AIzaSyDEmCjQ7IHGT6Da7eCzWvdddlXuMNx8CBs",
#   "authDomain": "ner-67bde.firebaseapp.com",
#   "databaseURL": "https://ner-67bde-default-rtdb.firebaseio.com",
#   "projectId": "ner-67bde",
#   "storageBucket": "ner-67bde.appspot.com",
#   "messagingSenderId": "120719853880",
#   "appId": "1:120719853880:web:6f85a9c5299eb69c8e19cd"
# }

# firebase = Firebase(config)
# class Todo(db.Model):
#     sno = db.Column(db.Integer, primary_key=True)
#     name = db.Column(db.String(200), nullable=False)
#     education = db.Column(db.String(500), nullable=False)
#     # date_created = db.Column(db.DateTime, default=datetime.utcnow)

#     def __repr__(self) -> str:
#         return f"{self.sno} - {self.title}"

@app.route('/', methods=['GET','POST'])
def hello_world():
    if request.method == 'POST':
        name = request.form.get("input_url")
        scrapped_reviews = test.get_url(name)
        # print(len())
        model.predicting_model(scrapped_reviews['reviews'].tolist())

        # model = BertForSequenceClassification.from_pretrained("./custom_trained_tested")  # Replace with your model path
        # tokenizer = BertTokenizer.from_pretrained("./custom_trained_tested")
        # if model == True and tokenizer == True:
        #     print(True)
        #return name, model,tokenizer
    
        # # return file
        # db.child("candidates").push(data)
        # return render_template('index.html')
        # return "submitted!!!"
    return render_template('testing_form.html')

if __name__ == "__main__":
    app.run(debug=True)