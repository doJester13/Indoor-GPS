package com.example.erik.wifidetection;


import android.content.Context;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.util.Log;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.util.EntityUtils;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

class PostTask extends AsyncTask<String, String, String> {

    // Server user register url
    public  String URL_INSERT = ""; // http://192.168.1.30:80/android_api/insert.php
    private Context mContext;
    //ProgressDialog dialog;
    SharedPreferences prefs;

    public PostTask(Context c){
        mContext = c;

    }

    protected void onPreExecute() {

        //dialog = new ProgressDialog(mContext);
        //dialog.setMessage("Loading...");
        //dialog.show();
        getDBURL();

    }

    @Override
    protected String doInBackground(String... data) {
        // Create a new HttpClient and Post Header
        HttpClient httpclient = new DefaultHttpClient();
        HttpPost httppost = new HttpPost(URL_INSERT);
        HttpResponse response = null;

        try {
            //add data to a dictionary key-value
            List<NameValuePair> pairs = new ArrayList<NameValuePair>();
            pairs.add(new BasicNameValuePair("man", data[0]));
            pairs.add(new BasicNameValuePair("prod", data[1]));
            pairs.add(new BasicNameValuePair("dt", data[2]));
            pairs.add(new BasicNameValuePair("lat", data[3]));
            pairs.add(new BasicNameValuePair("lon", data[4]));
            pairs.add(new BasicNameValuePair("fingerprint", data[5]));
            pairs.add(new BasicNameValuePair("tag", data[6]));
            httppost.setEntity(new UrlEncodedFormEntity(pairs));
            response= httpclient.execute(httppost);
            HttpEntity entity = response.getEntity();
            if (entity != null){
                String r = EntityUtils.toString(entity);
                Log.v("RESPONSE",r);

                return r;
            } else{
              return "0";
            }

        } catch (ClientProtocolException e) {

        } catch (IOException e) {

        }
        return "";
    }

    protected void onPostExecute(String res) {

           // dialog.dismiss();

    }

    public void getDBURL(){
            //Read the pref file
            prefs = mContext.getSharedPreferences(SettingsActivity.MY_PREFERENCES, Context.MODE_PRIVATE);
            URL_INSERT = prefs.getString(SettingsActivity.URLDB, "");
            Log.v("PREF",URL_INSERT);
    }
}