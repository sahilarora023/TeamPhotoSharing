package net.sahil.ApacheHttpClient;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.Random;

import org.apache.http.HttpEntity;
import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.entity.mime.MultipartEntity;
import org.apache.http.entity.mime.content.ByteArrayBody;
import org.apache.http.entity.mime.content.StringBody;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.util.EntityUtils;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Bitmap.CompressFormat;
import android.graphics.BitmapFactory;
import android.media.MediaScannerConnection;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

public class UploadActivity extends Activity {

	private MenuItem item;
	private String url = "http://www.sahilarora.net/PhotoSharing/PHPJavaServer.php";
	private File myDir;
	String fname;
	
	ImageView imageview;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.activity_upload);
		
		final EditText fromEditText = (EditText) findViewById(R.id.fromEditText);
		final EditText titleEditText = (EditText) findViewById(R.id.titleEditText);
		final EditText categoryEditText = (EditText) findViewById(R.id.categoryEditText);
		final EditText descEditText = (EditText) findViewById(R.id.descEditText);
		Button uploadButton = (Button) findViewById(R.id.uploadButton);
		Button selectPhotoButton = (Button) findViewById(R.id.btnSelectPhoto);
		ImageView cameraViewButton = (ImageView) findViewById(R.id.cameraViewButton);
		imageview = (ImageView) findViewById(R.id.imageView2);
		
		uploadButton.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				String fromParam;
				String titleParam;
				String categoryParam;
				String descParam;
				
				
					/*
					fromParam = URLEncoder.encode(fromEditText.getText().toString(), "utf-8");
					titleParam = URLEncoder.encode(titleEditText.getText().toString(), "utf-8");
					categoryParam = URLEncoder.encode(categoryEditText.getText().toString(), "utf-8");
					descParam = URLEncoder.encode(descEditText.getText().toString(), "utf-8");
					*/
					fromParam = fromEditText.getText().toString();
					titleParam = titleEditText.getText().toString();
					categoryParam = categoryEditText.getText().toString();
					descParam = descEditText.getText().toString();
					
					item.setActionView(R.layout.progress);
					SendHttpRequestTask t = new SendHttpRequestTask();
					
					String[] params = new String[]{url, fromParam, titleParam, categoryParam, descParam};
					t.execute(params);
					
				
				
			}
		});
		
		selectPhotoButton.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				pickPhotoIntent();
			}
			
		});
		
		cameraViewButton.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
				
					dispatchTakePictureIntent();
				
			}
			
		});
		
	}
	
	private void SaveImage(Bitmap finalBitmap) {
	    String root = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_PICTURES).toString();
	    myDir = new File(root + "/saved_images");
	    myDir.mkdirs();
	    Random generator = new Random();
	    int n = 10000;
	    n = generator.nextInt(n);
	    fname = "Image-" + n + ".jpg";
	    File file = new File(myDir, fname);
	    if (file.exists())
	        file.delete();
	    try {
	        FileOutputStream out = new FileOutputStream(file);
	        finalBitmap.compress(Bitmap.CompressFormat.JPEG, 90, out);
	        out.flush();
	        out.close();
	    }
	    catch (Exception e) {
	        e.printStackTrace();
	    }


	    // Tell the media scanner about the new file so that it is
	    // immediately available to the user.
	    MediaScannerConnection.scanFile(this, new String[] { file.toString() }, null,
	            new MediaScannerConnection.OnScanCompletedListener() {
	                public void onScanCompleted(String path, Uri uri) {
	                    Log.i("ExternalStorage", "Scanned " + path + ":");
	                    Log.i("ExternalStorage", "-> uri=" + uri);
	                }
	    });

	}
	private void dispatchTakePictureIntent() {
		
		Intent takePicture = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
		startActivityForResult(takePicture, 0);//zero can be replaced with any action code
	    
	}
	
	private void pickPhotoIntent() {
	Intent pickPhoto = new Intent(Intent.ACTION_PICK,
	           android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
	startActivityForResult(pickPhoto , 1);//one can be replaced with any action code
	}
	
	protected void onActivityResult(int requestCode, int resultCode, Intent imageReturnedIntent) { 
		super.onActivityResult(requestCode, resultCode, imageReturnedIntent); 
		switch(requestCode) {
		case 0:
		    if(resultCode == RESULT_OK){  
		    	 Bitmap bp = (Bitmap) imageReturnedIntent.getExtras().get("data");
		    	 SaveImage(bp);
		    	 imageview.setImageBitmap(bp);
		    	   
		    }

		break; 
		case 1:
		    if(resultCode == RESULT_OK){  
		        Uri selectedImage = imageReturnedIntent.getData();
		        imageview.setImageURI(selectedImage);
		    }
		break;
		}
		}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.main, menu);
		item = menu.getItem(0);
		return true;
	}


	private class SendHttpRequestTask extends AsyncTask<String, Void, String> {

		
		@Override
		protected String doInBackground(String... params) {
			String url = params[0];
			String fromParam = params[1];
			String titleParam = params[2];
			String categoryParam = params[3];
			String descParam = params[4];
			
			try {
				//Bitmap b = BitmapFactory.decodeResource(UploadActivity.this.getResources(), R.drawable.logo);	
				Bitmap b = BitmapFactory.decodeFile(myDir.getAbsolutePath()+"/" +fname);
				ByteArrayOutputStream baos = new ByteArrayOutputStream();
				b.compress(CompressFormat.PNG, 0, baos);
				
				HttpClient client = new DefaultHttpClient();
				
				HttpPost post = new HttpPost(url);
				MultipartEntity multiPart = new MultipartEntity();
				multiPart.addPart("file", new ByteArrayBody(baos.toByteArray(), fname));				
				multiPart.addPart("from", new StringBody(fromParam));
				multiPart.addPart("title", new StringBody(titleParam));
				multiPart.addPart("category", new StringBody(categoryParam));
				multiPart.addPart("desc", new StringBody(descParam));
				
				post.setEntity(multiPart);
				
		        
				HttpResponse response = client.execute(post);
				HttpEntity resEntity = response.getEntity();		
				String result = EntityUtils.toString(resEntity);
				//int code = response.getStatusLine().getStatusCode();
				
				return result;
				
			}
			catch(Throwable t) {
				// Handle error here
				t.printStackTrace();
			}
			

			
			return null;
		}

		@Override
		protected void onPostExecute(String result) {	
			Context context = getApplicationContext();
			CharSequence text = result;
			int duration = Toast.LENGTH_SHORT;

			Toast toast = Toast.makeText(context, text, duration);
			toast.show();
			item.setActionView(null);
			
		}
		
		
		
		
	}


}
