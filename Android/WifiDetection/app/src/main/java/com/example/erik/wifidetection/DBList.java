package com.example.erik.wifidetection;

import android.content.Context;
import android.database.Cursor;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.CursorAdapter;
import android.widget.ListView;
import android.widget.TextView;

import java.io.IOException;
import java.util.concurrent.ExecutionException;
import java.util.concurrent.TimeUnit;
import java.util.concurrent.TimeoutException;

public class DBList extends AppCompatActivity {

    private static final int QUERY_COMPLETE = 0;
    Handler handler;

    String output;
    private DbManager db=null;
    private CursorAdapter adapter;
    private ListView listview=null;
    Context ctx;
    PostTask pt;


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


        ctx = this;
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
                String tag=crs.getString(crs.getColumnIndex(DBHelper.FIELD_TAG));



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
                txt=(TextView) v.findViewById(R.id.txt_tag);
                txt.setText(tag);


            }

            @Override
            public long getItemId(int position){
                Cursor crs=adapter.getCursor();
                crs.moveToPosition(position);
                return crs.getLong(crs.getColumnIndex(DBHelper.FIELD_ID));
            }
        };

        listview.setAdapter(adapter);


        Button delete = (Button)findViewById(R.id.deleteAll);
        delete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                new Thread(new Runnable() {
                    @Override
                    public void run() {
                        Cursor x = db.query();
                        if (x != null) {
                            if (x.moveToFirst()) {
                                do {
                                    db.delete(x.getLong(x.getColumnIndex(DBHelper.FIELD_ID)));
                                } while (x.moveToNext());
                            }
                        }
                        //send message to handler
                        Message msg = Message.obtain();
                        msg.what = QUERY_COMPLETE;
                        handler.sendMessage(msg);
                    }

                }).start();

                handler = new Handler() {
                    @Override
                    public void handleMessage(Message msg) {
                        switch (msg.what) {
                            case QUERY_COMPLETE:
                                //when the message arrive, update the UI
                                Cursor y = db.query();
                                adapter.swapCursor(y);
                                adapter.notifyDataSetChanged();
                                break;

                        }
                    }
                };

            }
        });

        Button upload = (Button)findViewById(R.id.upload);
        upload.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {


                new Thread(new Runnable() {
                    @Override
                    public void run() {
                        Cursor x = db.query();

                        String manQ;
                        String proQ;
                        String dtQ;
                        String latQ;
                        String lonQ;
                        String fingerprintQ;
                        String tagQ;

                        if (x != null){
                            if (x.moveToFirst()){
                                do{
                                    manQ = x.getString(x.getColumnIndex(DBHelper.FIELD_MAN));
                                    proQ = x.getString(x.getColumnIndex(DBHelper.FIELD_PROD));
                                    dtQ = x.getString(x.getColumnIndex(DBHelper.FIELD_DATE));
                                    latQ = x.getString(x.getColumnIndex(DBHelper.FIELD_LAT));
                                    lonQ = x.getString(x.getColumnIndex(DBHelper.FIELD_LON));
                                    fingerprintQ = x.getString(x.getColumnIndex(DBHelper.FIELD_FINGERPRINT));
                                    tagQ = x.getString(x.getColumnIndex(DBHelper.FIELD_TAG));

                                    try {
                                        insertSurv(manQ, proQ, dtQ, latQ, lonQ, fingerprintQ, tagQ);
                                    } catch (IOException e) {
                                        e.printStackTrace();
                                    } catch (InterruptedException e) {
                                        e.printStackTrace();
                                    } catch (ExecutionException e) {
                                        e.printStackTrace();
                                    } catch (TimeoutException e) {
                                        e.printStackTrace();
                                    }
                                    if (output.compareTo("1") == 0){
                                        db.delete(x.getLong(x.getColumnIndex(DBHelper.FIELD_ID)));

                                    }
                                }while (x.moveToNext());
                            }
                        }
                        //send message to handler
                        Message msg = Message.obtain();
                        msg.what = QUERY_COMPLETE;
                        handler.sendMessage(msg);
                    }

                }).start();

                handler = new Handler(){
                    @Override
                    public void handleMessage(Message msg) {
                        switch(msg.what){
                            case QUERY_COMPLETE:
                                //when the message arrive, update the UI
                                Cursor  y = db.query();
                                adapter.swapCursor(y);
                                adapter.notifyDataSetChanged();
                                break;

                        }
                    }
                };

            }
        });
    }

    private void insertSurv(String man, String prod, String date, String lat, String lon, String fingerprint, String tag) throws IOException, ExecutionException, InterruptedException, TimeoutException {

        pt = new PostTask(ctx);
        output = pt.execute(man, prod, date, lat, lon, fingerprint, tag).get(5000, TimeUnit.MILLISECONDS);

    }
}




