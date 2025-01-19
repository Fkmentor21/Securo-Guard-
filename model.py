from transformers import BertTokenizer, BertForSequenceClassification
import torch
import numpy as np

def predicting_model(unlabeled_reviews):

# Load the trained model and tokenizer
    model = BertForSequenceClassification.from_pretrained(r"custom_trained_tested")  # Replace with your model path
    print('step 1')
    tokenizer = BertTokenizer.from_pretrained(r"custom_trained_tested")
    print('loaded')

# Tokenize the reviews
    inputs = tokenizer(unlabeled_reviews, truncation=True, padding=True, return_tensors="pt")

# Get predictions from the model
    with torch.no_grad():
        outputs = model(**inputs)
        logits = outputs.logits

# Convert logits to probabilities and predicted classes
    probabilities = torch.nn.functional.softmax(logits, dim=-1)
    predicted_classes = np.argmax(probabilities.numpy(), axis=1)  # 0 = fake, 1 = real

# Print the results
    for review, prediction, prob in zip(unlabeled_reviews, predicted_classes, probabilities):
        print(f"Review: {review}")
        print(f"Predicted Class: {'Real' if prediction == 1 else 'Fake'}")
        print(f"Confidence: Real = {prob[1]:.2f}, Fake = {prob[0]:.2f}")
        print("-" * 40)

# # Reviews to test (without labels)
# unlabeled_reviews = [
#  "I have long wanted to eat Teppanyaki style food and been thinking of going to Benihana for a longest time. But after reading reviews on Yelp for the one in Sunnyvale, CA, I was quite apprehensive.I had also heard good things about Benihana in Pittsburgh from my brother in law and so decided to go there whenever I goto Pittsburgh.So, finally, we went there after New Years and had a blast. We had to wait at the bar even after making reservation, but thats OK. At the table we were not rushed at all. The food was tasty and the chef was funny and interesting. Not to mention very entertaining and friendly bartender  - MARTY.Overall, it was one of the best restaurant experiences we ever had.",

# "Have ordered from Jim about 5 or 6 times and each order was flawless. They do everything right - efficient in taking an order, putting together a beautiful arrangement, and getting it delivered when it is needed. Our most recent order was a few days ago for a funeral viewing and it was very nice as always.  Great people, quality work, and you don't need to wonder if they are going to get it right. They are our go-to florist.",

# "OMG... I totally love this place! I am happy now that I do not have to go all the way to Chinatown for boba, but sad that they don't have more than one location.I order mostly from their milk tea menu, but sometimes get the slushies with milk too---it is not on the menu, but you can definitely order it. The fishballs are soooo good. I ordered them not spicy, but they still have somewhat of a tang to them.The staff is friendly and their service is speedy, but it depends on how many people are there. I have never found this place empty!You gotta go here!Note: Don't forget to get a stamp card! 10 stamps and you a get free boba! :)",

# "Theresa and Joanne at Forever Young Aesthetics are a dream team! Joanne was amazingly kind, accommodating, and responsive to all of my complicated appointment booking needs (I reside in Flagstaff and wished to make the trip to Surprise just for Theresa's permanent makeup artistry) and Theresa simply has to be the very best in the business! Not only was the procedure for permanent eyeliner and brows discussed thoroughly with me prior to choosing a single color, I was made to feel completely at ease and fully confident in my choice to proceed. As one would expect from a talent like Theresa, the results of my permanent makeup are absolutely perfect and, simply put, stunning. In fact, I was making my way through a local grocery store less than 30 minutes post procedure and was stopped twice in the aisles for compliments on the color of my eyes. I my book, that's a five star review for Forever Young Aesthetics right there!A million thanks are owed to Theresa and Joanne for their phenomenal customer service and the fantastic results! When you can enter a business a customer and leave a friend, you know you've found something truly special.",

# "Probably the only restaurant that the service was so terrible that I have left before getting my food. Me and my boyfriend went here with a groupon, that we had purchased specifically for Fujo. They refused to take it, and the manager told us, ""you have two choices, ask for a refund from groupon or just eat here without it."" If we had been rude then I wouldn't be able to blame him, but we had politely and calmly spoke to them and asked about it. We walked out and went to Emzy for sushi instead and it had great service and excellent food.",

# "This bar is cramped, overpriced, and the service is unbelievably slow. Happy Hour runs 4-6, so you'll show up at 5, have no place to sit or stand, and will only be able to get *maybe* 2 drinks during happy hours You'll then be told that the upstairs (which is actually not bad) will be opening at 5, even though you're asking at 5:20 (this has seriously happened all three times I've been there).Seriously, avoid this place. When one of your co-workers suggests a work happy hour, direct them anywhere else."


# ]
# predicting_model(unlabeled_reviews)