    # split the dataset
from pandas import Series
series = Series.from_csv('daily-minimum-temperatures-test-sublime.csv', header=0)
split_point = len(series) - 8
dataset, validation = series[0:split_point], series[split_point:]
print('Dataset %d, Validation %d' % (len(dataset), len(validation)))
dataset.to_csv('dataset.csv')
validation.to_csv('validation.csv')