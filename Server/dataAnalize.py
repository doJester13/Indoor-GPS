import math
import re

f = open("data.csv","r")
w = open("analized.csv", "w")
#txt = f.read()
#print txt
s = ","
end = "\n"
coords = {
	'laboratorio wireless - 3': [1, 5],
	'laboratorio wireless - 4': [5, 5],
	'laboratorio wireless - 2': [1, 9],
	'laboratorio wireless - 5': [5, 9],
	'laboratorio wireless - 1': [1, 13],
	'laboratorio wireless - 6': [5, 13],
	'cr - 1': [1, 14],
	'cr - 2': [4, 14],
	'cr - 3': [7, 14],
	'cr - 6': [1, 17],
	'cr - 5': [4, 17],
	'cr - 4': [7, 17],
	'bagno - 1': [9, 17],
	'bagno - 2': [9, 14],
	'atrio - 1': [7, 9],
	'atrio - 2': [7, 13],
	'atrio - 3': [9, 13],
	'atrio - 4': [10, 13],
	'atrio - 5': [9, 9],
	'atrio - 6': [10, 9],
	'atrio - 7': [10, 5],
	#'CO1': [13 ,11],
	#'CO2': [16 ,11],
	'sem 2 - 1': [23 ,17],
	'sem 2 - 2': [19 ,17],
	'sem 2 - 3': [21 ,16],
	'sem 2 - 4': [21 ,15],
	'sem 2 - 5': [23 ,14],
	'sem 2 - 6': [19 ,14],
	'lab b - 1': [13, 8],
	'lab b - 2': [16, 8],
	'lab b - 3': [19, 8],
	'lab b - 4': [22, 8],
	'lab b - 5': [22, 5],
	'lab b - 6': [19, 5],
	'lab b - 7': [16, 5],
	'lab b - 8': [13, 5],
	'lab a - 1': [10, 4],
	'lab a - 2': [13, 4],
	'lab a - 3': [16, 4],
	'lab a - 4': [19, 4],
	'lab a - 5': [22, 4],
	'lab a - 6': [22, 1],
	'lab a - 7': [19, 1],
	'lab a - 8': [16, 1],
	'lab a - 9': [13, 1],
	'lab a - 10' : [10, 1]
}

def dist(pointA, pointB):
	d = math.sqrt(((pointA[0] - pointB[0]) **2) + ((pointA[1] - pointB[1]) **2))
	return d


#print(coords)
#print(coords['cr - 2'])
#print(dist(coords['cr - 2'],coords['lab a - 4']))
for linea in f.readlines():
    results =linea.split(",")
    good = results[0]
    print good
    w.write(good+s)
    for i in range(1, len(results)):
    	if(good.strip() == results[i].strip()):
    		w.write(str(1)+s)
    	else:
    		w.write(str(0)+s)
    	
    	truncG = re.sub('-.*', '', good)
    	truncT = re.sub('-.*', '', results[i])
    	#print truncG, truncT
    	
    	if(truncG.strip() == truncT.strip()):
    		w.write(str(1)+s)
    	else:
    		w.write(str(0)+s)

    	meter = dist(coords[good.strip()],coords[results[i].strip()])
    	w.write(str(meter)+s)
    w.write(end)

w.close()
f.close()


