# dc:a5:f4:ff:1d:00
$map2 << EOD
1 13 -78
1 14 -81
1 17 -82
1 5 -69
1 9 -76

4 14 -82
4 17 -79

5 13 -77
5 5 -71
5 9 -78

7 13 -73
7 14 -76
7 17 -77
7 9 -76

9 13 -72
9 14 -81
9 17 -81
9 9 -74

10 1 -70
10 13 -70
10 4 -57
10 5 -53
10 9 -61

13 1 -68
13 11 0
13 4 -64
13 5 -50
13 8 -62

16 1 -64
16 11 0
16 4 -66
16 5 -59
16 8 -63

19 1 -76
19 14 -85
19 17 0
19 4 -72
19 5 -52
19 8 -68

21 15 0
21 16 0

22 1 -81
22 4 -76
22 5 -59
22 8 -63

23 14 -84
23 17 -72
EOD

set pm3d map
set dgrid3d
splot "$map2" using 1:2:3