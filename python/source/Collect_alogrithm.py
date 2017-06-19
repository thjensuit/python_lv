#!/usr/bin/env python

# Code source: Jaques Grobler
# License: BSD 3 clause
# Use Min Temperature to predict Precipitation

import matplotlib.pyplot as plt
import os
from numpy import genfromtxt, savetxt
import numpy as np
from sklearn.datasets import load_iris
from collections import Counter
from math import sqrt, expm1
import sys, getopt

from sklearn import datasets, linear_model, cross_validation
#from sknn.mlp import Regressor, Layer
from sklearn.neural_network import MLPRegressor
from sklearn import linear_model, neighbors, tree, ensemble,svm

from sklearn.metrics import mean_absolute_error, mean_squared_error, median_absolute_error
import json



def caculateIndex(arrPredict,test_y,dirPath = ""):
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

	with open(dirPath, "ab") as myfile:
		myfile.write("ME: %s,MEA: %s,RMSE: %s\n" % (AVG_ME,AVG_MEA,AVG_RMSE))

def caculateMetrics(test_y,predict_y):
	print mean_absolute_error(test_y,predict_y),mean_squared_error(test_y,predict_y),median_absolute_error(test_y,predict_y)

def PrecipitationSVR(train_x,train_y,test_x,real_y,outputfile):
	regr = svm.SVR(kernel='rbf', C=1e3, gamma=0.1)
	predict_y = regr.fit(train_x, train_y).predict(test_x)
	savetxt(outputfile+'precipitation_predict_.txt', predict_y, delimiter=',')
	result = [outputfile+'precipitation_predict_.txt']
	if len(real_y)>0:
		caculateIndex(predict_y,real_y,outputfile+"precipitation_index_.txt")
		result = [outputfile+'precipitation_predict_.txt',outputfile+"precipitation_index_.txt"]
	return json.dumps(result)

def PrecipitationRF(train_x,train_y,test_x,real_y,outputfile):
	regr = ensemble.RandomForestRegressor(bootstrap=True, criterion='mse', max_depth=None, max_features='auto',
		random_state=3, min_samples_leaf=1, min_samples_split=2,n_estimators=100, n_jobs=2, oob_score=False,verbose=0)
	predict_y = regr.fit(train_x, train_y).predict(test_x)
	savetxt(outputfile+'precipitation_predict_.txt', predict_y, delimiter=',')
	result = [outputfile+'precipitation_predict_.txt']
	if len(real_y)>0:
		caculateIndex(predict_y,real_y,outputfile+"precipitation_index_.txt")
		result = [outputfile+'precipitation_predict_.txt',outputfile+"precipitation_index_.txt"]
	return json.dumps(result)

def TemperatureMLP(train_x,train_y,test_x,real_y,outputfile):
	regr = MLPRegressor(hidden_layer_sizes=(500, ),activation="identity",
									solver="lbfgs",learning_rate="adaptive")
	predict_y = regr.fit(train_x, train_y).predict(test_x)
	savetxt(outputfile+'temperature_predict_.txt', predict_y, delimiter=',')
	result = [outputfile+'temperature_predict_.txt']
	if len(real_y)>0:
		caculateIndex(predict_y,real_y,outputfile+"temperature_index_.txt")
		result = [outputfile+'temperature_predict_.txt',outputfile+"temperature_index_.txt"]
	return json.dumps(result)

argv = sys.argv[1:]
training = '' # link data train
predict = '' # link data predict
real = '' # link data real
outputfile = '' # output path
month = '' # link month data follow using our system
typesys = '' # precication, tempurature, wind ...
config = '' # relate attribute ...

try:
  opts, args = getopt.getopt(argv,"hi:o:m:p:r:t:c:",["ifile=","ofile=","month=","predict=","real=","typesys=","config="])
except getopt.GetoptError:
  print 'test.py -i <training> -o <outputfile> -m <month> -p <predict> -r <real> -t <typesys> -c <config>'
  sys.exit(2)
for opt, arg in opts:
  if opt == '-h':
     print 'test.py -i <training> -o <outputfile> -m <month> -p <predict> -r <real> -t <typesys> -c <config>'
     sys.exit()
  elif opt in ("-i", "--ifile"):
     training = arg
  elif opt in ("-p", "--predict"):
     predict = arg
  elif opt in ("-r", "--real"):
     real = arg
  elif opt in ("-o", "--ofile"):
     outputfile = arg
  elif opt in ("-m", "--month"):
     month = arg
  elif opt in ("-c", "--config"):
     config = arg #hien thi cai gia tri lien quan
  elif opt in ("-t", "--typesys"):
     typesys = arg #du bao ve gi mua , nhiet do

# This part for client upload file
if training != "" and outputfile != "" and predict != "":
	dataset = genfromtxt(open(training,'r'), delimiter=',', dtype='f8')[0:]
	train_x = dataset[:,1:]
	train_y = dataset[:,0]
	predict_set = genfromtxt(open(predict,'r'), delimiter=',', dtype='f8')[0:]
	test_x = predict_set[:,:]
	real_y = [];
	if real != "":
		real_y = genfromtxt(open(real,'r'), delimiter=',', dtype='f8')[0:]

	if  typesys == '1':
		print PrecipitationSVR(train_x,train_y,test_x,real_y,outputfile)
	elif typesys == '3':
		print TemperatureMLP(train_x,train_y,test_x,real_y,outputfile)

#this part user using our database
if month != "" and outputfile != "" and predict != "":
	# structure our database 0:Date 1:Precipitation 2:RelativeHumidity 3:MaxTemperature $:Solar 5:MinTemperature 6:Wind
	arrRelated = map(int, config.split(','))
	dataset = genfromtxt(open(month,'r'), delimiter=',', dtype='f8')[0:]
	train_x = dataset[:-30,arrRelated]
	train_y = dataset[:-30,1]
	predict_set = genfromtxt(open(predict,'r'), delimiter=',', dtype='f8')[0:]
	test_x = predict_set[:,:]
	real_y = [];
	if real != "":
		real_y = genfromtxt(open(real,'r'), delimiter=',', dtype='f8')[0:]
	#print train_x,train_y,test_x
	if typesys == '1':
		print PrecipitationSVR(train_x,train_y,test_x,real_y,outputfile)
	elif typesys == '3':
		print TemperatureMLP(train_x,train_y,test_x,real_y,outputfile)

