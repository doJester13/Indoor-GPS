#ec:35:86:36:46:20
$map2 << EOD
1 13 0
1 14 0
1 17 0
1 5 0
1 9 0

4 14 0
4 17 0

5 13 -90
5 5 0
5 9 0

7 13 0
7 14 0
7 17 0
7 9 0

9 13 0
9 14 0
9 17 0
9 9 0

10 1 -89
10 13 0
10 4 -90
10 5 -90
10 9 -90

13 1 -90
13 11 0
13 4 -91
13 5 -86
13 8 -87

16 1 -85
16 11 0
16 4 -84
16 5 -87
16 8 -90

19 1 -86
19 14 0
19 17 0
19 4 -88
19 5 -85
19 8 -85

21 15 0
21 16 0

22 1 -81
22 4 -79
22 5 -86
22 8 -85

23 14 0
23 17 0
EOD

set pm3d map
set dgrid3d
splot "$map2" using 1:2:3