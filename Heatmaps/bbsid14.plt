# dc:a5:f4:ff:1d:01
$map2 << EOD
1 13 -79
1 14 -81
1 17 -82
1 5 -70
1 9 -75

4 14 -81
4 17 -83

5 13 -78
5 5 -71
5 9 -79

7 13 -74
7 14 -75
7 17 -79
7 9 -77

9 13 -71
9 14 -79
9 17 -80
9 9 -73

10 1 -69
10 13 -69
10 4 -60
10 5 -54
10 9 -59

13 1 -65
13 11 0
13 4 -66
13 5 -50
13 8 -70

16 1 -64
16 11 0
16 4 -67
16 5 -57
16 8 -60

19 1 -74
19 14 -85
19 17 0
19 4 -71
19 5 -57
19 8 -68

21 15 0
21 16 -91

22 1 -85
22 4 -77
22 5 -61
22 8 -61

23 14 -84
23 17 -85
EOD

set pm3d map
set dgrid3d
splot "$map2" using 1:2:3