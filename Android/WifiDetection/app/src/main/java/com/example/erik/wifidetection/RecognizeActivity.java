package com.example.erik.wifidetection;

import android.content.Context;
import android.net.wifi.ScanResult;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.support.v4.app.NavUtils;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import org.osmdroid.ResourceProxy;
import org.osmdroid.tileprovider.tilesource.ITileSource;
import org.osmdroid.tileprovider.tilesource.XYTileSource;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.TimeoutException;

public class RecognizeActivity extends AppCompatActivity {

    Context context;
    WifiManager mWifiManager;
    List<ScanResult> wifiList;

    //MapView map;

    final ArrayList<String> listp = new ArrayList<String>();

    String tileList[] = {"http://tile.openstreetmap.org/"};

    final ITileSource tileSource = new XYTileSource("Mapnik", ResourceProxy.string.mapnik, 15, 19, 2048, ".png",tileList );

    String fingerprint;

    String output;
    RecTask pt;
    TextView out;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_recognize);

        ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            actionBar.setHomeButtonEnabled(true);
            actionBar.setDisplayHomeAsUpEnabled(true);
        }


        context = getApplicationContext();

        final Button track = (Button)findViewById(R.id.trackButton);
        out = (TextView)findViewById(R.id.outputText);


        //WiFi check
        mWifiManager = (WifiManager) getSystemService(Context.WIFI_SERVICE);
        if (!mWifiManager.isWifiEnabled()) {
            Toast.makeText(context, "wifi is disabled..making it enabled", Toast.LENGTH_LONG).show();
            mWifiManager.setWifiEnabled(true);
        }

        //map = (MapView) findViewById(R.id.map);
        //final IMapController mapController = map.getController();

        //location manager and location listener

       /* map.setTileSource(tileSource);
        map.setBuiltInZoomControls(false);
        map.setMultiTouchControls(true);
        //set map cache
        map.getOverlayManager().getTilesOverlay().setOvershootTileCache(500);*/

        track.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                track();
                try {
                    sendFingerprint(RecognizeActivity.this, fingerprint, out);
                } catch (IOException e) {
                    e.printStackTrace();
                } catch (ExecutionException e) {
                    e.printStackTrace();
                } catch (InterruptedException e) {
                    e.printStackTrace();
                } catch (TimeoutException e) {
                    e.printStackTrace();
                }
            }
        });

    }

    public void track() {
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

            //fingerprint
            fingerprint = "";
            for (int i = 0; i < listp.size(); i++) {
                fingerprint = listp.get(i) + "," + fingerprint;
            }
            if (fingerprint.charAt(fingerprint.length() - 1) == ',') {
                fingerprint = fingerprint.substring(0, fingerprint.length() - 1);
            }

        }
        Log.v("IMPORTANT", fingerprint);

    }
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            // Respond to the action bar's Up/Home button
            case android.R.id.home:
                NavUtils.navigateUpFromSameTask(this);
                return true;
        }
        return super.onOptionsItemSelected(item);
    }

    private void sendFingerprint(Context c, String fingerprint, TextView v) throws IOException, ExecutionException, InterruptedException, TimeoutException {

        pt = new RecTask(c);
        output = pt.execute(fingerprint).get(5000, TimeUnit.MILLISECONDS);
        Log.v("OUTPUT",output);
        output = output.replace("<br>","\n");
        v.setText(output);
    }



}
