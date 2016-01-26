package com.example.erik.wifidetection;

import android.content.Context;
import android.content.Intent;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;
import android.os.Build;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import org.osmdroid.ResourceProxy;
import org.osmdroid.api.IMapController;
import org.osmdroid.bonuspack.overlays.MapEventsOverlay;
import org.osmdroid.bonuspack.overlays.MapEventsReceiver;
import org.osmdroid.bonuspack.overlays.Marker;
import org.osmdroid.tileprovider.tilesource.ITileSource;
import org.osmdroid.tileprovider.tilesource.XYTileSource;
import org.osmdroid.util.GeoPoint;
import org.osmdroid.views.MapView;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

public class MainActivity extends AppCompatActivity {

    WifiManager mWifiManager;
    List<ScanResult> wifiList;

    Context context;
    Button reload;
    EditText tag;

    String MANUFACTER;
    String PRODUCT;
    String ts;
    String fingerprint;
    double mlat;
    double mlon;
    String label;

    double lat;
    double lon;
    // DB Manager
    private DbManager db=null;

    // All static variables
    String URL = "";

    //list of String
    final ArrayList<String> listp = new ArrayList<String>();

    String tileList[] = {"http://tile.openstreetmap.org/"};

    final ITileSource tileSource = new XYTileSource("Mapnik", ResourceProxy.string.mapnik, 15, 19, 2048, ".png",tileList );


    /* good idea?
    final float scale = getBaseContext().getResources().getDisplayMetrics().density;
    final int newScale = (int) (256 * scale);
    String[] OSMSource = new String[2];
    OSMSource[0] = "http://a.tile.openstreetmap.org/";
    OSMSource[1] = "http://b.tile.openstreetmap.org/";
    XYTileSource MapSource = new XYTileSource("OSM", null, 1, 18, newScale, ".png", OSMSource);
    map.setTileSource(MapSource);*/

    MapView map;
    Marker m;
    Boolean init;
    GeoPoint point;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_list);

        //init
        reload = (Button) (findViewById(R.id.reloadButton));
        tag = (EditText) (findViewById(R.id.tag));
        context = getApplicationContext();
        db=new DbManager(context);

        //get the device information
        MANUFACTER = Build.MANUFACTURER;
        PRODUCT = Build.PRODUCT;



        //WiFi check
        mWifiManager = (WifiManager) getSystemService(Context.WIFI_SERVICE);
        if (!mWifiManager.isWifiEnabled()) {
            Toast.makeText(context, "wifi is disabled..making it enabled", Toast.LENGTH_LONG).show();
            mWifiManager.setWifiEnabled(true);
        }

        //list from layout
        //final ListView mylist = (ListView) findViewById(R.id.listView1);
        //new adapter
        //final ArrayAdapter<String> adapter = new ArrayAdapter<String>(this, android.R.layout.simple_list_item_1, listp);


        reload.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                listp.clear();
                if (mWifiManager.isWifiEnabled()) {
                    if (mWifiManager.startScan()) {
                        //List of available APs
                        wifiList = mWifiManager.getScanResults();
                        if (wifiList != null && !wifiList.isEmpty()) {
                            for (ScanResult scan : wifiList) {
                                //int level = WifiManager.calculateSignalLevel(scan.level,20);
                                //Log.v(scan.BSSID, String.valueOf(level));
                                //for each connection, add a string in the list
                                listp.add(scan.BSSID + ": " + scan.level + "dBm");
                            }
                        }
                    }
                }
                //adapter.notifyDataSetChanged();
                //injecton of data
                //mylist.setAdapter(adapter);



                //timestamp
                ts = getCurrentTimeStamp();
                label = tag.getText().toString();
                if (label.compareTo("") == 0){
                    label = ts;
                }
                fingerprint = "";
                for (int i=0;i<listp.size();i++){
                    fingerprint =listp.get(i)+ ","+ fingerprint;
                }
                if(fingerprint.charAt(fingerprint.length()-1) == ','){
                    fingerprint = fingerprint.substring(0,fingerprint.length()-1);
                }


                Toast.makeText(context,"Data captured",Toast.LENGTH_SHORT).show();
                /*Log.v("DATA", MANUFACTER);
                Log.v("DATA",PRODUCT);
                Log.v("DATA",ts);
                Log.v("DATA",String.valueOf(mlat));
                Log.v("DATA",String.valueOf(mlon));
                Log.v("DATA", fingerprint);*/

                //save on internal DB
                db.save(MANUFACTER, PRODUCT, ts, String.valueOf(mlat),String.valueOf(mlon), fingerprint, label);

            }
        });

        //get the location to use as center of mapView

        map = (MapView) findViewById(R.id.map);
        final IMapController mapController = map.getController();
        // map need to be initialized
        init = true;

        LocationManager locationManager = (LocationManager) this.getSystemService(Context.LOCATION_SERVICE);

        LocationListener locationListener = new LocationListener() {
            public void onLocationChanged(Location location) {
                // Called when a new location is found by the network location provider.
                if(init){
                    lat = location.getLatitude();
                    lon = location.getLongitude();
                    GeoPoint x = new GeoPoint(lat,lon);
                    mapController.setCenter(x);
                    init = false;
                    Log.v("IMPORTANT", String.valueOf(lat)+"/"+String.valueOf(lon));
                }
            }

            public void onStatusChanged(String provider, int status, Bundle extras) {}

            public void onProviderEnabled(String provider) {}

            public void onProviderDisabled(String provider) {}
        };

        //locationManager.requestLocationUpdates(locationManager.NETWORK_PROVIDER, 1, 0, locationListener);
        locationManager.requestSingleUpdate(locationManager.NETWORK_PROVIDER,locationListener,null);

        //map settings
        map.setTileSource(tileSource);
        map.setBuiltInZoomControls(false);
        map.setMultiTouchControls(true);
        //set map cache
        map.getOverlayManager().getTilesOverlay().setOvershootTileCache(500);

        /*GeoPoint startPoint;
        if (lat!=-1 && lon!=-1){
            startPoint = new GeoPoint(lat, lon);
        }else {
            startPoint = new GeoPoint(44.59, 11.30);
        }
        mapController.setCenter(startPoint);*/
        mapController.setZoom(18);

        //set map event receiver
        MapEventsReceiver mReceive = new MapEventsReceiver() {

            @Override
            public boolean singleTapConfirmedHelper(GeoPoint geoPoint) {
                return false;
            }

            @Override
            public boolean longPressHelper(GeoPoint geoPoint) {
                point = geoPoint;
                mlat = geoPoint.getLatitude();
                mlon = geoPoint.getLongitude();
                if (map.getOverlays().contains(m)){
                    map.getOverlays().remove(m);
                }
                m = new Marker(map);
                m.setPosition(geoPoint);
                m.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                map.getOverlays().add(m);
                map.invalidate();
                String pos = String.valueOf(mlat) + "/" + String.valueOf(mlon);
                Toast toast = Toast.makeText(getApplicationContext(),pos, Toast.LENGTH_SHORT);
                toast.show();

                Log.v("LATLONG", pos);
                /*URL = "http://nominatim.openstreetmap.org/reverse?format=xml&lat="+mlat+"&lon="+mlon+"&zoom=18&addressdetails=1";
                new CallXML().execute(URL);*/



                return false;
            }

        };

        //Creating a handle overlay to capture the gestures
        MapEventsOverlay OverlayEventos = new MapEventsOverlay(getBaseContext(), mReceive);
        map.getOverlays().add(OverlayEventos);
        //Refreshing the map to draw the new overlay
        map.invalidate();

        Button up = (Button) findViewById(R.id.buttonUp);
        Button down = (Button) findViewById(R.id.buttonDown);
        Button right = (Button) findViewById(R.id.buttonRight);
        Button left = (Button) findViewById(R.id.buttonLeft);

        up.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (map.getOverlays().contains(m)){
                    map.getOverlays().remove(m);
                }
                point.setLatitudeE6(point.getLatitudeE6() + (int) (0.00001 * 1E6));
                m.setPosition(point);
                m.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                map.getOverlays().add(m);
                map.invalidate();
            }
        });
        down.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (map.getOverlays().contains(m)){
                    map.getOverlays().remove(m);
                }
                point.setLatitudeE6(point.getLatitudeE6()-(int)(0.00001*1E6));
                m.setPosition(point);
                m.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                map.getOverlays().add(m);
                map.invalidate();
            }
        });
        right.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (map.getOverlays().contains(m)){
                    map.getOverlays().remove(m);
                }
                point.setLongitudeE6(point.getLongitudeE6() + (int) (0.00001 * 1E6));
                m.setPosition(point);
                m.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                map.getOverlays().add(m);
                map.invalidate();
            }
        });
        left.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (map.getOverlays().contains(m)){
                    map.getOverlays().remove(m);
                }
                point.setLongitudeE6(point.getLongitudeE6() - (int)(0.00001*1E6));
                m.setPosition(point);
                m.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
                map.getOverlays().add(m);
                map.invalidate();
            }
        });


    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();

        //noinspection SimplifiableIfStatement
        if (id == R.id.setting) {
            Intent intent = new Intent(this, SettingsActivity.class);
            this.startActivity(intent);
            return true;
        }
        if (id == R.id.dblist) {
            Intent intent = new Intent(this, DBList.class);
            this.startActivity(intent);
            return true;
        }

        return super.onOptionsItemSelected(item);
    }

    /*private void _getLocation() {
        // Get the location manager
        LocationManager locationManager = (LocationManager)getSystemService(LOCATION_SERVICE);
        Criteria criteria = new Criteria();
        String bestProvider = locationManager.getBestProvider(criteria, false);
        locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,1000L,500.0f, locat);

        Location location = locationManager.getLastKnownLocation(bestProvider);
        try {
            lat = location.getLatitude();
            lon = location.getLongitude();
        } catch (NullPointerException e) {
            lat = -1.0;
            lon = -1.0;
        }
    }*/
    public static String getCurrentTimeStamp(){
        try {

            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            String currentTimeStamp = dateFormat.format(new Date()); // Find todays date

            return currentTimeStamp;
        } catch (Exception e) {
            e.printStackTrace();

            return null;
        }
    }




}