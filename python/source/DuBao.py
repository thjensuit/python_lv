from pandas import read_csv
from pandas import datetime
from matplotlib import pyplot
import matplotlib.pyplot as plt
from pandas import DataFrame
from statsmodels.tsa.arima_model import ARIMA
from math import sqrt, expm1
import numpy as np
from sklearn.metrics import mean_squared_error
from numpy import genfromtxt, savetxt
import sys, getopt
import os

import json
# reference: https://machinelearningmastery.com/make-sample-forecasts-arima-python/
from pandas import Series
from statsmodels.tsa.arima_model import ARIMA
import numpy

def saveImage(y_true, y_pred, path,title):
	print y_true
	x = range(1,len(y_true)+1)
	fig = plt.figure()
	plt.plot(x, y_true, label='Thuc Do'.format(i=1))
	plt.plot(x, y_pred,linestyle="--", linewidth =2, label='Du Bao'.format(i=2),color="red")
	plt.legend(loc='best')
	plt.subplots_adjust(top=0.83)
	arrTitle = title.split(",")
	plt.title(title+"\n")
	fig.savefig(path, dpi=fig.dpi*2)

def dochinhxac(y_true,y_pred):
	result = [];
	for i in range(0,len(y_true)):
		if y_true[i] > 0:
			lech = abs(y_pred[i] - y_true[i])/y_true[i]
			result.insert(0,1-lech)
	return np.mean(result)

def caculateIndex(arrPredict,test_y,dirPath = "",strDetail=""):
	key = 0;
	Sum_ME = 0;
	Sum_MEA = 0;
	Sum_RMSE = 0;
	for i in arrPredict:
		# caculate
		Sum_ME += (arrPredict[key]-test_y[key])
		# (MAE)
		Sum_MEA += abs(arrPredict[key]-test_y[key])
		#(RMSE):
		Sum_RMSE += (arrPredict[key]-test_y[key])**2
		key = key+1
	AVG_ME=Sum_ME/(key+1)
	AVG_MEA=Sum_MEA/(key+1)
	AVG_RMSE= sqrt(Sum_RMSE/(key+1))
	PercentChinhXac = dochinhxac(test_y,arrPredict)
	with open(dirPath, "ab") as myfile:
		myfile.write("%s, ME: %s,MEA: %s,RMSE: %s, Do Chinh Xac: %s\n" % (strDetail, AVG_ME,AVG_MEA,AVG_RMSE,PercentChinhXac))


# create a differenced series
def difference(dataset, interval=1):
	diff = list()
	for i in range(interval, len(dataset)):
		value = dataset[i] - dataset[i - interval]
		diff.append(value)
	return numpy.array(diff)

# invert differenced value
def inverse_difference(history, yhat, interval=1):
	return yhat + history[-interval]


argv = sys.argv[1:]
training = '' # link data train for user
predict = '' # link data predict
real = '' # link data real
outputfile = '' # output path
# month = '' # link month data follow using our system
month = 1 # link month data follow using our system
typesys = '' # precication, tempurature, wind ...
days_predict = 30 # precication, tempurature, wind ...

try:
  opts, args = getopt.getopt(argv,"hi:o:m:r:t:d:",["ifile=","ofile=","month=","real=","typesys=","dayspredict="])
except getopt.GetoptError:
  print 'test.py -i <training> -o <outputfile> -m <month> -r <real> -t <typesys> -d <dayspredict>'
  sys.exit(2)
for opt, arg in opts:
  if opt == '-h':
     print 'test.py -i <training> -o <outputfile> -m <month> -r <real> -t <typesys>'
     sys.exit()
  elif opt in ("-i", "--ifile"):
     training = arg
  elif opt in ("-r", "--real"):
     real = arg
  elif opt in ("-o", "--ofile"):
     outputfile = arg
  elif opt in ("-m", "--month"):
     month = int(arg)
  elif opt in ("-t", "--typesys"):
     typesys = int(arg) #du bao ve gi mua , nhiet do
  elif opt in ("-d", "--dayspredict"):
     days_predict = int(arg) #how many day for predict

dirpath = os.path.dirname(os.path.abspath(__file__))
# day_of_months = [0,31,28,31,30,31,30,31,31,30,31,30,31]
day_of_months = [1]
dataset = genfromtxt(open(dirpath+'/../data/'+str(month)+'.csv','r'), delimiter=',', dtype='f8')[0:]
X = dataset[:,typesys]
days_in_year = day_of_months[int(month)]; #create a observertion
differenced = difference(X, days_in_year)

# fit model
if typesys == 1:#mua
	model = ARIMA(differenced, order=(5,1,0))
elif typesys == 2:#doam
	model = ARIMA(differenced, order=(1,0,0))
elif typesys == 3:#nhietdocao
	model = ARIMA(differenced, order=(5,1,0))
elif typesys == 4:#nang luong mat troi
	model = ARIMA(differenced, order=(2,1,0))
elif typesys == 5:# nhiet do thap
	model = ARIMA(differenced, order=(4,1,0))
elif typesys == 6:# gio
	model = ARIMA(differenced, order=(2,0,1))


model_fit = model.fit(disp=0)
# multi-step out-of-sample forecast
start_index = len(differenced)+1
end_index = start_index + (days_predict-2) # -> preidct 30 next
forecast = model_fit.predict(start=start_index, end=end_index)
# invert the differenced forecast to something usable
#print forecast, len(forecast)
history = [x for x in X]
day = 1
y_pred = []
for yhat in forecast:
	inverted = inverse_difference(history, yhat, days_in_year)
	history.append(inverted)
	y_pred.append(inverted)
	day += 1


real_y = [];
if real != "":
	real_y = genfromtxt(open(real,'r'), delimiter=',', dtype='f8')[0:]


import time
millis = int(round(time.time() * 1000))
savetxt(outputfile+'dubao_predict_'+str(millis)+'.txt', y_pred, delimiter=',')
result = [outputfile+'dubao_predict_'+str(millis)+'.txt']
if len(real_y)>0:
	caculateIndex(y_pred,real_y,outputfile+"dubao_index_"+str(millis)+".txt")
	result = [outputfile+'dubao_predict_'+str(millis)+'.txt',outputfile+"dubao_index_"+str(millis)+".txt"]
print json.dumps(result)
