package com.example.erik.wifidetection;


import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

public class DBHelper extends SQLiteOpenHelper{

    public static final String DBNAME="DBSURVEYS";

    public static final String TBL_NAME = "scan";

    public static final String FIELD_ID = "_id";
    public static final String FIELD_MAN= "manufacturer";
    public static final String FIELD_PROD = "product";
    public static final String FIELD_DATE = "data";
    public static final String FIELD_LAT = "lat";
    public static final String FIELD_LON = "lon";
    public static final String FIELD_FINGERPRINT = "fingerprint";

    public static String DBPATH = "";

    public DBHelper(Context context) {
        super(context, DBNAME, null, 1);
        DBPATH = context.getDatabasePath(DBNAME).getPath();
    }

    @Override
    public void onCreate(SQLiteDatabase db) {
        String q="CREATE TABLE "+TBL_NAME+
                " ( "+FIELD_ID+" INTEGER PRIMARY KEY AUTOINCREMENT," +
                FIELD_MAN+" TEXT," +
                FIELD_PROD+" TEXT," +
                FIELD_DATE+" TEXT," +
                FIELD_LAT+" TEXT," +
                FIELD_LON+" TEXT," +
                FIELD_FINGERPRINT+" TEXT)";
        db.execSQL(q);
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {

    }
}
