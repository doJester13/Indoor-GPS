# 00:3a:99:2c:76:71 
$map2 << EOD
1 13 0
1 14 0
1 17 0
1 5 -88
1 9 -88

4 14 0
4 17 0

5 13 -88
5 5 0
5 9 0

7 13 -88
7 14 0
7 17 0 
7 9 -88

9 13 -84
9 14 -84
9 17 0
9 9 -84

10 1 -86
10 13 -82
10 4 -81
10 5 -81
10 9 -87

13 1 -88
13 11 0
13 4 -84
13 5 -77
13 8 -79

16 1 -81
16 11 0
16 4 -80
16 5 -67
16 8 -69

19 1 -81
19 14 -40
19 17 -43
19 4 -79
19 5 -68
19 8 -74

21 15 -45
21 16 -37

22 1 -85
22 4 -73
22 5 -69
22 8 -72

23 14 -40
23 17 -45
EOD

set pm3d map
set dgrid3d
splot "$map2" using 1:2:3