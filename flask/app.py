from flask import Flask, jsonify, request
from flask_cors import CORS

import tensorflow as tf
from tensorflow.keras.preprocessing.text import Tokenizer
from tensorflow.keras.preprocessing.sequence import pad_sequences
from tensorflow.keras.callbacks import EarlyStopping
import pandas as pd
import numpy as np
import pickle
import os.path
import math

import re
import emoji
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.keys import Keys
import platform

import time
from datetime import datetime
import pytz

oov_tok = "<OOV>"
trunc_type = 'post'
embedding_dim = 128
bilstm_dim = 160
dense_dim1 = 64
dropout_val1 = 0.3
dense_dim2 = 64
dropout_val2 = 0.2
num_epochs = 100


early_stopping = EarlyStopping(
        monitor='val_accuracy',
        patience=10,
        verbose=1,
        restore_best_weights=True
    )

def case_folding(text):
    return text.lower()

#NOISE REMOVAL
def noise_removal(text):
    # Menghapus emojis
    text = emoji.replace_emoji(text, replace='')
    #menghapus http / https
    text = re.sub(r'https?://\S+', '', text)
    #menghapus hashtag
    text = re.sub(r'#\S+', '', text)
    #menghapus angka
    text = re.sub(r'(?<!\w)\b\d+\b(?!\w)', '', text)
    #menghapus special character
    text = re.sub(r'[^\w\s]', '', text)
    #menghapus spasi yang berlebihan
    text = re.sub(r'\s+', ' ', text).strip()
    return text

#SLANG WORD
slang_word_df = pd.read_csv("kamus_slangword.csv")
slang_dict = slang_word_df.set_index('slang')['formal'].to_dict()
def convert_to_formal(sentence):
    words = sentence.split()
    replaced_sentence = [slang_dict.get(word, word) for word in words]
    return ' '.join(replaced_sentence)

#Stemming
stemmer = StemmerFactory().create_stemmer()
def stem_text(text):
    return stemmer.stem(text)

#Stopword Removal
stopper = StopWordRemoverFactory().create_stop_word_remover()
def remove_stopwords(text):
    return stopper.remove(text)

def preprocessing(text):
    return remove_stopwords(
        stem_text(
            convert_to_formal(
                noise_removal(
                    case_folding(text)
                )
            )
        )
    )

categories = ['non-cyberbullying', 'flaming', 'harassment', 'denigration']

MODEL_PATH = "my_model.keras"
model = tf.keras.models.load_model(MODEL_PATH,custom_objects=None, compile=True)

def predict_categories(text):
    text = preprocessing(text)
    with open('tokenizer.pickle', 'rb') as handle:
        tokenizer = pickle.load(handle)
    input_sequence = tokenizer.texts_to_sequences([text])
    input_data = tf.keras.preprocessing.sequence.pad_sequences(input_sequence, maxlen=120, truncating='post')
    prediction = model.predict(input_data)
    prediction_max_index = int(np.argmax(prediction))
    return prediction_max_index

def initialize_driver():
    chrome_options = webdriver.ChromeOptions()
    chrome_options.add_argument('--window-size=1920,1080')
    return webdriver.Chrome(options=chrome_options)

def login(socialMedia, wd):
    wait = WebDriverWait(wd, 20)
    if(socialMedia == 'threads'):
        wd.get('https://www.threads.net/login')
        input_field = wait.until(EC.presence_of_element_located((By.XPATH, "//input[@placeholder='Username, phone or email' and @type='text']")))
        input_field.send_keys('youngcreative.ds')

        input_password = wait.until(EC.presence_of_element_located((By.XPATH, "//input[@placeholder='Password' and @type='password']")))
        input_password.send_keys('youngerstronger21')

        login_button = wait.until(EC.element_to_be_clickable((By.XPATH, "//div[@role='button' and .//div[text()='Log in']]")))
        login_button.click()
    else:
        wd.get('https://x.com/i/flow/login')
        input_field = wait.until(EC.presence_of_element_located((By.NAME, 'text')))
        input_field.send_keys('cenz1919')

        next_button = wait.until(EC.element_to_be_clickable((By.XPATH, '//button[@role="button" and .//span[text()="Next"]]')))
        next_button.click()

        password_field = wait.until(EC.presence_of_element_located((By.NAME, 'password')))
        password_field.send_keys('masterwheels123')
        
        login_button = wait.until(EC.element_to_be_clickable((By.XPATH, '//button[@data-testid="LoginForm_Login_Button"]')))
        login_button.click()

def threads(maxScraped, keyword):
    wd = initialize_driver()
    collected_threads = []
    wait = WebDriverWait(wd, 20)

    try:
        login('threads', wd)
        time.sleep(5)

        search_button = wait.until(EC.element_to_be_clickable((By.XPATH, "//a[@href='/search']")))
        search_button.click()

        input_keyword = wait.until(EC.presence_of_element_located((By.XPATH, "//input[@placeholder='Search' and @type='search']")))
        input_keyword.send_keys(keyword)
        input_keyword.send_keys(Keys.RETURN)

        while len(collected_threads) < maxScraped:
            threads_cells = wd.find_elements(By.CSS_SELECTOR, 'div.x78zum5.xdt5ytf')
            for cell in threads_cells:
                try:
                    threads_text_element = cell.find_element(By.XPATH, './/div[contains(@class, "x1a6qonq") and contains(@class, "x6ikm8r") and contains(@class, "x10wlt62")][not(@hidden)]')
                    thread_text = threads_text_element.text.strip()
                except Exception as e:
                    thread_text = None

                if thread_text == None:
                    continue

                try:
                    get_username = cell.find_element(By.XPATH, './/a[starts-with(@href, "/@")]')
                    username = get_username.text.strip()
                except Exception as e:
                    username = 'unknown'

                try:
                    get_time = cell.find_element(By.XPATH, './/time[@data-visualcompletion="ignore-dynamic"]')
                    post_time = get_time.get_attribute('title')
                except Exception as e:
                    post_time = 'unknown'
                
                if thread_text and thread_text not in [str(thread['text']) for thread in collected_threads]:
                    predicted_index = predict_categories(thread_text)
                    collected_threads.append({'text': thread_text, 'label': predicted_index, 'username': '@'+username, 'social':'Threads', 'time': post_time})
                    if len(collected_threads) >= maxScraped:
                        break

                if len(collected_threads) >= maxScraped+1:
                    break
            wd.execute_script("window.scrollBy(0, 500);")
            time.sleep(2)

    except Exception as e:
        return jsonify({'status': 'error', 'msg': 'Scraping failed due to an error: {}'.format(str(e))}), 500
    finally:
        wd.quit()

    if not collected_threads:
        return jsonify({'status': 'error', 'msg': 'No data was scraped. Please try again or modify your search parameters.'}), 200


    df = pd.DataFrame(collected_threads)
    excel_filename = 'temp.xlsx'
    df.to_excel(excel_filename, index=False, engine='openpyxl')
    return jsonify({'status': 'oke', 'posts': collected_threads})

def twitter(maxScraped, keyword):
    wd = initialize_driver()
    collected_tweets = []
    wait = WebDriverWait(wd, 20)
    try:
        login('x', wd)
    except Exception as e:
        print(f"Login error: {e}")
        wd.quit()
        return None

    try:
        # Navigate to explore page
        explore_link = WebDriverWait(wd, 10).until(
            EC.element_to_be_clickable((By.XPATH, '//a[@href="/explore" and @aria-label="Search and explore"]'))
        )
        explore_link.click()

        # Enter search keyword
        search_input = WebDriverWait(wd, 10).until(
            EC.presence_of_element_located((By.XPATH, '//input[@aria-label="Search query"]'))
        )
        search_input.send_keys(keyword)
        search_input.send_keys(Keys.RETURN)
        time.sleep(5)

        # Scrape tweets
        while len(collected_tweets) < maxScraped:
            tweet_cells = wait.until(EC.presence_of_all_elements_located((By.CSS_SELECTOR, 'div[data-testid="cellInnerDiv"]')))

            for cell in tweet_cells:
                try:
                    tweet_text_elements = cell.find_element(By.CSS_SELECTOR, 'div[data-testid="tweetText"]')
                    print(tweet_text_elements)
                    if tweet_text_elements == None:
                        continue
                    tweet_text = tweet_text_elements.text.strip()
                except Exception as e:
                    tweet_text = 'unknown'
                    
                try:
                    get_username = cell.find_element(By.XPATH, './/a[@role="link" and @tabindex="-1"]//div[@style="text-overflow: unset; color: rgb(113, 118, 123);"]//span[@style="text-overflow: unset;"]')
                    post_username = get_username.text.strip()
                except Exception as e:
                    post_username = 'unknown'

                try:
                    get_time = cell.find_element(By.XPATH, './/div//a[@dir="ltr" and @role="link"]//time')
                    post_time = get_time.get_attribute('datetime')
                except Exception as e:
                    post_time = 'unknown'

                if tweet_text and tweet_text not in [str(tweet['text']) for tweet in collected_tweets]:
                    if(tweet_text != 'unknown'):
                        predicted_index = predict_categories(tweet_text)
                        collected_tweets.append({'text': tweet_text, 'label': predicted_index, 'username': post_username, 'social': 'X', 'time': post_time})

                if len(collected_tweets) >= maxScraped:
                    break

            # Scroll to load more tweets
            wd.execute_script("window.scrollBy(0, 800);")
            time.sleep(2)
    except Exception as e:
        print(f"Error during tweet scraping: {e}")

    wd.quit()
    df = pd.DataFrame(collected_tweets)
    excel_filename = 'temp.xlsx'
    df.to_excel(excel_filename, index=False, engine='openpyxl')
    return jsonify({'status': 'oke', 'posts': collected_tweets})

app = Flask(__name__)
CORS(app)

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    text = data.get('text')
    prediction = predict_categories(text)
    return jsonify({'label_index': prediction})

@app.route('/sendPost', methods=['POST'])
def post():
    data = request.json
    socialMedia = data.get('socialMedia')
    text = data.get('text')
    try:
        wd = initialize_driver()
        wait = WebDriverWait(wd, 20)
        if socialMedia == 'threads':
            login('threads', wd)
            time.sleep(5)
            post_button = wait.until(EC.element_to_be_clickable(
                (By.XPATH, "//div[contains(@class, 'xc26acl') and contains(@class, 'x78zum5') and text()='Post' or text()='Kirim']")
            ))
            post_button.click()
            time.sleep(5)
            wait.until(lambda driver: driver.execute_script("return document.activeElement !== null && document.activeElement.isContentEditable"))
            wd.switch_to.active_element.send_keys(text)
            time.sleep(5)
            wd.switch_to.active_element.send_keys(Keys.COMMAND, Keys.ENTER)
            time.sleep(5)
        else:
            login('x', wd)
            time.sleep(5)
            wd.switch_to.active_element.send_keys('n')
            time.sleep(5)
            wd.switch_to.active_element.send_keys(text)
            time.sleep(5)
            wd.switch_to.active_element.send_keys(Keys.COMMAND, Keys.ENTER)
            time.sleep(5)
        return jsonify({"status": "success"})
    except Exception as e:
        return jsonify({'status':"failed"})
    finally:
        wd.quit()

@app.route('/scrape', methods=['POST'])
def scrape():
    data = request.json
    maxScraped = int(data.get('maxData'))
    socialMedia = data.get('socialMedia')
    keyword = data.get('keyword')

    if socialMedia == 'threads':
        return threads(maxScraped,keyword)
    elif socialMedia == 'x':
        return twitter(maxScraped,keyword + " lang:id filter:replies")

@app.route('/count_labels', methods=['POST'])
def count_labels():
    df = pd.read_excel("dataset.xlsx")
    label_counts = df['label'].value_counts().to_dict()
    print(label_counts)
    labels = {}
    for i in range(4):
        labels[i] = label_counts[i] 

    return jsonify({"labels": labels})

@app.route('/temp', methods=['POST'])
def temp():
    path = 'temp.xlsx'
    df = pd.read_excel(path)
    data = df.to_dict(orient='records')
    mod_time = os.path.getmtime(path)
    indonesia_tz = pytz.timezone('Asia/Jakarta')
    # Convert the timestamp to a datetime object in the specified timezone
    datetime_obj = datetime.fromtimestamp(mod_time, indonesia_tz)
    # Format the datetime object to AM/PM format
    formatted_time = datetime_obj.strftime('%I:%M %p')
    return jsonify({"temp":data, 'time':formatted_time})

@app.route('/dataset', methods=['POST'])
def dataset():
    path = 'dataset.xlsx'
    df = pd.read_excel(path)
    data = df.to_dict(orient='records')
    return jsonify({"dataset": data})

@app.route('/update_label', methods=['POST'])
def update_label():
    data = request.json
    post_id = int(data.get('postId'))
    new_label = data.get('newLabel')
    df = pd.read_excel("temp.xlsx")
    if post_id < len(df):
        df.at[post_id, 'label'] = new_label
        df.to_excel("temp.xlsx", index=False)
        return jsonify({"message": "Label updated successfully"})
    else:
        return jsonify({"error": "Post ID not found"}), 400

@app.route('/retrain', methods=['POST'])
def retrain():
    #baca file latest crawl
    temp_df= (
        pd.read_excel("temp.xlsx")
        .dropna()
        .drop_duplicates(subset=['text'], keep='last')
    )

    #buat salinan file latest crawl (belom di preprocessing)
    temp_raw_df = temp_df.copy()

    #preprocessing
    temp_df['text'] = temp_df['text'].apply(lambda text: preprocessing(text))
    preprocessed_temp_df = temp_df[['text', 'label', 'username', 'social', 'time']]

    #baca preprocessed dataset sebelumnya
    preprocessed_dataset_df = (
        pd.read_excel("preprocessed_dataset.xlsx")
        .dropna()
    )

    #gabung hasil crawling dengan dataset sebelumnya
    combined_df = (
        pd.concat([preprocessed_dataset_df, preprocessed_temp_df], ignore_index=True)
        .drop_duplicates(subset=['text'], keep='last')
    )

    combined_df['text_length'] = combined_df['text'].apply(len)
    mean_length = combined_df['text_length'].mean()
    std_length = combined_df['text_length'].std()
    print(mean_length)
    print(std_length)

    #shuffle dataset
    shuffled_df = combined_df.sample(frac=1, random_state=42).reset_index(drop=True)

    #splitting data
    train_size = int(0.80 * len(shuffled_df))

    train_df = shuffled_df.iloc[:train_size]
    # # test_df = shuffled_df.iloc[train_size:]

    train_text, train_label = train_df['text'].tolist(), train_df['label'].tolist()
    # # test_text, test_label = test_df['text'].tolist(), test_df['label'].tolist()


    #tokenizer
    tokenizer = Tokenizer(num_words=2000, oov_token=oov_tok)
    tokenizer.fit_on_texts(train_text)
    vocab_size = len(tokenizer.word_index) + 1

    
    #text to sequence
    train_sequences = tokenizer.texts_to_sequences(train_text)
    # # test_sequences = tokenizer.texts_to_sequences(test_text)

    #hitung jarak simpangan rata-rata dari nilai rata-rata
    padded_length = math.ceil(mean_length + std_length)

    #sequence to padded sequence
    train_padded = pad_sequences(train_sequences, maxlen=padded_length, truncating=trunc_type)
    # # test_padded = pad_sequences(test_sequences, maxlen=max_length, truncating=trunc_type)


    #define the model    
    model = tf.keras.Sequential([
        tf.keras.layers.Embedding(input_dim=vocab_size, output_dim=embedding_dim),
        tf.keras.layers.Bidirectional(tf.keras.layers.LSTM(bilstm_dim, recurrent_activation='sigmoid')),
        tf.keras.layers.Dense(dense_dim1, activation='relu'),
        tf.keras.layers.Dropout(dropout_val1),
        tf.keras.layers.Dense(dense_dim2, activation='relu'),
        tf.keras.layers.Dropout(dropout_val2),
        tf.keras.layers.Dense(4, activation='softmax')
    ])
    model.compile(loss='sparse_categorical_crossentropy',
              optimizer='adam',
              metrics=['accuracy'])
    
    # train model
    history = model.fit(
        train_padded, 
        np.array(train_label), 
        epochs=num_epochs, 
        batch_size = 32,
        validation_split=0.2,
        callbacks=[early_stopping]
    )

    # test_predictions = model.predict(test_padded)
    # test_predictions = np.argmax(test_predictions, axis=1)  # Convert probabilities to class predictions


    #save the model
    model.save('my_model.keras')

    #save the dictionary
    with open('tokenizer.pickle', 'wb') as handle:
        pickle.dump(tokenizer, handle, protocol=pickle.HIGHEST_PROTOCOL)

    #save the combined dataset (preprocessed) to preprocessed_dataset.xlsx
    combined_df = combined_df.drop(columns=['text_length'])

    # Save the DataFrame to an Excel file without the 'text_length' column
    combined_df.to_excel("preprocessed_dataset.xlsx", index=False)


    #read the dataset
    dataset_df = pd.read_excel("dataset.xlsx")
    #Also save the raw combined dataset with the crawl data
    raw_df = (
        pd.concat([dataset_df,temp_raw_df], ignore_index=True)
        .drop_duplicates(subset=['text'], keep='last')
    )
    raw_df.to_excel("dataset.xlsx", index=False)

    return jsonify({
        'message': 'ok',
    })
    
if __name__ == '__main__':
  app.run(port=5000, debug=True)