package com.example.erik.wifidetection;


import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteException;
import android.widget.Toast;

public class DbManager {

    private DBHelper dbhelper;
    Context c;

    public DbManager(Context ctx){
        c= ctx;
        dbhelper = new DBHelper(ctx);
    }

    public void save(String man,String prod, String date, String lat, String lon, String fingerprint){
        SQLiteDatabase db=dbhelper.getWritableDatabase();
        ContentValues cv=new ContentValues();
        cv.put(DBHelper.FIELD_MAN,man);
        cv.put(DBHelper.FIELD_PROD,prod);
        cv.put(DBHelper.FIELD_DATE,date);
        cv.put(DBHelper.FIELD_LAT,lat);
        cv.put(DBHelper.FIELD_LON,lon);
        cv.put(DBHelper.FIELD_FINGERPRINT,fingerprint);

        try{
            db.insert(DBHelper.TBL_NAME,null,cv);
        } catch (SQLiteException e){

        }
        Toast.makeText(c,"Data Saved",Toast.LENGTH_SHORT).show();
    }

    public boolean delete(long id){
        SQLiteDatabase db=dbhelper.getWritableDatabase();
        String idQ="";
        idQ = String.valueOf(id);
        try{
            if (db.delete(DBHelper.TBL_NAME, DBHelper.FIELD_ID + "=" + idQ, null)>0)
                return true;
            return false;
        } catch (SQLiteException sqle){
            return false;
        }
    }

    public Cursor query(){
        Cursor crs=null;
        try{
            SQLiteDatabase db=dbhelper.getReadableDatabase();
            crs=db.query(DBHelper.TBL_NAME, null, null, null, null, null, null);
        } catch(SQLiteException sqle){
            return null;
        }
        return crs;
    }
}

