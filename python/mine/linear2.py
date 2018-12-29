import matplotlib.pyplot as plt
import numpy as np
from sklearn import datasets, linear_model
from sklearn.metrics import mean_squared_error, r2_score
#https://machinelearningmastery.com/grid-search-arima-hyperparameters-with-python/
# Print RMSE
#https://datascienceplus.com/linear-regression-in-python-predict-the-bay-areas-home-prices/
import pandas as pd
from pandas import Series
from matplotlib import pyplot
from statsmodels.tsa.arima_model import ARIMA
from sklearn.metrics import mean_squared_error
from math import sqrt

from sklearn.linear_model import LinearRegression

sf = pd.read_csv('daily-minimum-temperatures.csv')
sf.head()

# sf.drop(sf.columns[[0]], axis=1, inplace=True)
sf.info()

sf["temperatures"] = sf["Daily minimum temperatures in Melbourne, Australia, 1981-1990"]

corr_matrix = sf.corr()
corr_matrix["Daily minimum temperatures in Melbourne, Australia, 1981-1990"].sort_values(ascending=False)
sf.drop(sf.columns[[1]], axis=1, inplace=True)
# print(sf);

def get_group(x):
    if x < 5:
        return 'low_temp'
    elif (x >= 5) and (x < 10):
        return 'high_temp_low_freq'
    else:
        return 'high_temp_high_freq'

sf['group'] = sf.temperatures.apply(get_group)

print(sf);

# def predict(coef, history):
#     yhat = 0.0
#     for i in range(1, len(coef)+1):
#         yhat += coef[i-1] * history[-i]
#     return yhat

# series = Series.from_csv('daily-minimum-temperatures.csv', header=0)
# X = series.values
# M = series.axes
# M2 = series[series==1]
# size = len(X) - 7
# train, test = X[0:size], X[size:]
# history = [x for x in train]
# predictions = list()
# a = list()
# X = list()  # put your dates in here
# y = list()  # put your kwh in here
# # a = a.append(M)
# for index, val in series.iteritems():
#     # print index, val
#     a.append([index, val])
#     X.append([index])
#     y.append(val)
# # print(X[0:1])
# # print(M[0:1])
# # print(M2)
# # print(M2.axes)
# # print(a)
# # print(series)
# # print(M2.values)
# # print(a)
# # import numpy as np
# # X = np.array([[1, 1], [1, 2], [2, 2], [2, 3]])
# # print X
# # print y

# # y = np.dot(X, np.array([1, 2])) + 3
# model = LinearRegression()
# # model.fit(X, y)
# model.fit([['2012-04-13', 0.344230]], [0.344230])

# X_predict = [['2012-04-13', 0.353484]]  # put the dates of which you want to predict kwh here
# y_predict = model.predict(X_predict)
# print(y_predict)
