package com.example.erik.wifidetection;

import android.content.Context;
import android.database.Cursor;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CursorAdapter;
import android.widget.ListView;
import android.widget.TextView;

public class DBList extends AppCompatActivity {

    private DbManager db=null;
    private CursorAdapter adapter;
    private ListView listview=null;

    private View.OnClickListener clickListener=new View.OnClickListener()
    {
        @Override
        public void onClick(View v)
        {
            int position=listview.getPositionForView(v);
            long id=adapter.getItemId(position);
            if (db.delete(id))
                adapter.changeCursor(db.query());
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dblist);
        Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        /*
        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Snackbar.make(view, "Replace with your own action", Snackbar.LENGTH_LONG)
                        .setAction("Action", null).show();
            }
        });*/

        db=new DbManager(this);
        TextView dbpath=(TextView) findViewById(R.id.path);
        dbpath.setText(DBHelper.DBPATH);


        listview=(ListView) findViewById(R.id.DB_Entry);
        Cursor crs=db.query();
        adapter=new CursorAdapter(this, crs, 0){
            @Override
            public View newView(Context ctx, Cursor arg1, ViewGroup arg2){
                View v=getLayoutInflater().inflate(R.layout.dblist_row, null);
                return v;
            }
            @Override
            public void bindView(View v, Context arg1, Cursor crs){
                String man=crs.getString(crs.getColumnIndex(DBHelper.FIELD_MAN));
                String prod=crs.getString(crs.getColumnIndex(DBHelper.FIELD_PROD));
                String data=crs.getString(crs.getColumnIndex(DBHelper.FIELD_DATE));
                String lat=crs.getString(crs.getColumnIndex(DBHelper.FIELD_LAT));
                String lon=crs.getString(crs.getColumnIndex(DBHelper.FIELD_LON));
                String finger=crs.getString(crs.getColumnIndex(DBHelper.FIELD_FINGERPRINT));



                TextView txt=(TextView) v.findViewById(R.id.txt_man);
                txt.setText(man);
                txt=(TextView) v.findViewById(R.id.txt_prod);
                txt.setText(prod);
                txt=(TextView) v.findViewById(R.id.txt_date);
                txt.setText(data);
                txt=(TextView) v.findViewById(R.id.txt_lat);
                txt.setText(lat);
                txt=(TextView) v.findViewById(R.id.txt_lon);
                txt.setText(lon);
                txt=(TextView) v.findViewById(R.id.txt_finger);
                txt.setText(finger);


            }

            @Override
            public long getItemId(int position){
                Cursor crs=adapter.getCursor();
                crs.moveToPosition(position);
                return crs.getLong(crs.getColumnIndex(DBHelper.FIELD_ID));
            }
        };

        listview.setAdapter(adapter);
    }
}




