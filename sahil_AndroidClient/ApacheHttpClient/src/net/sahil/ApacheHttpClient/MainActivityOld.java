package net.sahil.ApacheHttpClient;

import java.io.BufferedReader;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URI;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;

import android.app.Activity;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

public class MainActivityOld extends Activity {

	private MenuItem item;
	private String url = "http://www.sahilarora.net/app/updatedata.php";
	EditText responseEditText;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		
		final EditText edtTxtOccupancy = (EditText) findViewById(R.id.editTextOccupancy);
		final EditText edtTxtSpotid = (EditText) findViewById(R.id.editTextSpotid);
		Button btnSend = (Button) findViewById(R.id.buttonSend);
		Button btnDownload = (Button) findViewById(R.id.btnDownload);
		Button btnUpload = (Button) findViewById(R.id.btnUpload);
		

		responseEditText = (EditText) findViewById(R.id.edtResp);
		
		btnSend.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				String occupancy = edtTxtOccupancy.getText().toString();
				String spotid = edtTxtSpotid.getText().toString();
				item.setActionView(R.layout.progress);
				SendHttpRequestTask t = new SendHttpRequestTask();
				
				String[] params = new String[]{url, occupancy, spotid};
				t.execute(params);
			}
		});
		
		btnDownload.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				Intent i = new Intent(MainActivityOld.this, DownloadActivity.class);
				startActivity(i);
			}
		});	

		

		
		btnUpload.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				Intent i = new Intent(MainActivityOld.this, UploadActivity.class);
				startActivity(i);
			}
		});		

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
			String occupancy = params[1];
			String spotid = params[2];
			
			String data = sendHttpRequest(url, occupancy, spotid);
			System.out.println("Data ["+data+"]");
			return data;
		}

		@Override
		protected void onPostExecute(String result) {
			responseEditText.setText(result);
			item.setActionView(null);
			
		}
		
		
		
	}

	private String sendHttpRequest(String url, String occupancy, String spotid) {
		StringBuffer buffer = new StringBuffer();
		try {
			System.out.println("URL ["+url+"] - Occupancy ["+occupancy+"] - Spotid ["+spotid+"]");
			
			
			// Apache HTTP Reqeust
			
			URI website = new URI(url+"?o="+occupancy+"&"+"s="+spotid);
            
			HttpClient client = new DefaultHttpClient();
			HttpGet request = new HttpGet();
			request.setURI(website);
			HttpResponse resp = client.execute(request);
			
			// We read the response
			InputStream is  = resp.getEntity().getContent();			
			BufferedReader reader = new BufferedReader(new InputStreamReader(is));
            StringBuilder str = new StringBuilder();
            String line = null;
            while((line = reader.readLine()) != null){
                str.append(line + "\n");
            }
            is.close();
            buffer.append(str.toString());			
			// Done!
		}
		catch(Throwable t) {
			t.printStackTrace();
		}
		
		return buffer.toString();
	}
}
