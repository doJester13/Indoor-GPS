package com.example.erik.wifidetection;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.Button;

public class MainActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Button sAtt =(Button)findViewById(R.id.ScanActivity);
        sAtt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent x = new Intent(MainActivity.this, MainScanActivity.class);
                MainActivity.this.startActivity(x);
            }
        });

        Button rAtt =(Button)findViewById(R.id.RecognizeActivity);
        rAtt.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent x = new Intent(MainActivity.this,RecognizeActivity.class);
                MainActivity.this.startActivity(x);
            }
        });

    }

}
