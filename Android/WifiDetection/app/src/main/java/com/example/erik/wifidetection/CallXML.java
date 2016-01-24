package com.example.erik.wifidetection;


import android.os.AsyncTask;
import android.util.Log;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

import java.util.ArrayList;
import java.util.HashMap;

public class CallXML extends AsyncTask<String,Void,String> {

    // XML node keys
    static final String KEY_ITEM = "result"; // parent node
    static final String osmId = "osm_id";
    static final String osmType = "osm_type";
    String url;


    @Override
    protected String doInBackground(String... urls) {
        Element e;
        String id = "";
        String type = "";
        url= urls[0];
        ArrayList<HashMap<String, String>> menuItems = new ArrayList<HashMap<String, String>>();
        XMLParser parser = new XMLParser();
        String xml = parser.getXmlFromUrl(url); // getting XML
        Document doc = parser.getDomElement(xml); // getting DOM element

        NodeList nl = doc.getElementsByTagName(KEY_ITEM);
        if (nl.getLength()>=1){
            e = (Element)nl.item(0);
            id = e.getAttribute(osmId);
            type = e.getAttribute(osmType);
        }

        Log.v("NODE", id);
        Log.v("NODE", type);
        return null;
    }
}
