package com.example.erik.wifidetection;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class SettingsActivity extends AppCompatActivity {
    //file name
    public final static String MY_PREFERENCES = "Config";

    public final static String URLDB = "URL";
    EditText urldb;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_settingsactivity);
        updatePreferencesData();

        Button save = (Button) findViewById(R.id.savePref);
        save.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                savePreferencesData(v);
            }
        });
    }

    private void updatePreferencesData(){
        //Read the pref file
        SharedPreferences prefs = getSharedPreferences(MY_PREFERENCES, Context.MODE_PRIVATE);

        String textData = prefs.getString(URLDB, "No Preferences!");

        urldb = (EditText) findViewById(R.id.URLDB);
        urldb.setText(textData);
    }

    public void savePreferencesData(View view) {
        // //Read the pref file
        SharedPreferences prefs = getSharedPreferences(MY_PREFERENCES, Context.MODE_PRIVATE);

        SharedPreferences.Editor editor = prefs.edit();


        CharSequence textData = urldb.getText();
        if (textData != null) {
            // Save the string
            editor.putString(URLDB, textData.toString());
            editor.commit();
        }
        updatePreferencesData();
    }
}
