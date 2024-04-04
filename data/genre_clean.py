import numpy as np
import pandas as pd


df = pd.read_csv('book_genre.csv')


# expand the dataset by splitting the genre column and creating a new row for each genre
df['genre'] = df['genre'].str.split(',')
df = df.explode('genre')

# remove rows with empty genre
df = df[df['genre'] != '']

# remove non alphanumeric characters
df['genre'] = df['genre'].str.replace(r'\W', ' ', regex=True)

# remove leading and trailing whitespaces
df['genre'] = df['genre'].str.strip()



# save the cleaned dataset
df.to_csv('book_genre_clean.csv', index=False)