set pm3d map interpolate 0,0
set dgrid3d
set cbrange [-120:0]
set palette defined(-120 "black", -100 "blue", -50 "red", -30 "yellow", 0 "white")  
set colorbox
splot "bssid4.plt" using 1:2:3