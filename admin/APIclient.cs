using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using System.Net.Http;
using System.Net.Http.Formatting;
using Newtonsoft.Json; // JSON feldolgozashoz
using System.IO; // filekezeleshez
using System.Net.Http.Headers; // MediaTypeHeaderValue() -hoz
using System.Net.NetworkInformation; // netkapcsolat ellenorzesehez

namespace filmlexikon
{
	public class APIclient
	{
		private string apiBase = "";
		private string apiEndpoint = "";
		private string apiKey = "";

		public APIclient()
		{
			// konstruktor, betoltjuk a configot, az exe mellett levo filmlexikon.config.json

			if( File.Exists( "filmlexikon.config.json" ) ){

				string text = File.ReadAllText( "filmlexikon.config.json" );

				Dictionary<string, string> cfg = JsonConvert.DeserializeObject<Dictionary<string, string>>( text );

				apiBase = cfg[ "apiBase" ];
				apiEndpoint = cfg[ "apiEndpoint" ];
				apiKey = cfg[ "apiKey" ];

			}

		}

		public class ApiResponse
		{
			public string status { get; set; }
			public string msg { get; set; }
			public List<Movies> movies { get; set; }
			public List<Persons> persons { get; set; }

		}

		public async Task<ApiResponse> callMultipart( Dictionary<string, string> requestParams )
		{
			if( apiBase == "" || apiEndpoint == "" || apiKey == "" ){

				ApiResponse missingConfig = new ApiResponse();

				missingConfig.msg = "Hiányzó vagy hibás config file";

				return missingConfig;
			}

			// megnezzuk van-e netkapcsolat

			try {

				var ping = new Ping();
				var pingResult = ping.Send( "www.google.com", 1000 );
				if ( pingResult == null || pingResult.Status != IPStatus.Success )
				{
					ApiResponse pingResponse = new ApiResponse();

					pingResponse.msg = "Nincs internetkapcsolat";

					return pingResponse;
				}

			}
			catch( PingException )
			{
				ApiResponse pingResponse = new ApiResponse();

				pingResponse.msg = "Nincs internetkapcsolat";

				return pingResponse;
			}

			var multipartFormContent = new MultipartFormDataContent();

			// az API key-t minden esetben hozzaadjuk a kereshez

			multipartFormContent.Add( new StringContent( apiKey ), name: "apiKey" );

			foreach( var p in requestParams ) {

				// a parametereket hozaadjuk a lekereshez
				multipartFormContent.Add( new StringContent( p.Value ), name: p.Key );

			}

			// akkor kezdodhet http-vel, ha filmet modositunk, vagy uj filmnel URL-bol jelenitjuk meg, ilyenkor nem toltunk fel plakatot

			if( requestParams.ContainsKey( "poster" ) && requestParams[ "poster" ] != "" && ! requestParams[ "poster" ].StartsWith( "http" ) )
			{
				// ha a parameterek kozt kaptunk poster-t, akkor azt file-kent feltoltjuk
				var fileStreamContent = new StreamContent( File.OpenRead( requestParams[ "poster" ] ) );
				fileStreamContent.Headers.ContentType = new MediaTypeHeaderValue( "image/jpg" );
				multipartFormContent.Add( fileStreamContent, name: "poster", fileName: "poster.jpg" );
			}

			if ( requestParams.ContainsKey( "photo" ) && requestParams[ "photo" ] != "" && ! requestParams[ "photo" ].StartsWith( "http" ) )
			{
				// ha a parameterek kozt kaptunk photo-t, akkor azt file-kent feltoltjuk
				var fileStreamContent = new StreamContent( File.OpenRead( requestParams[ "photo" ] ) );
				fileStreamContent.Headers.ContentType = new MediaTypeHeaderValue( "image/jpg" );
				multipartFormContent.Add( fileStreamContent, name: "photo", fileName: "photo.jpg" );
			}

			// HttpClient
			HttpClient client = new HttpClient();

			// API szerver meghivasa
			var response = await client.PostAsync( this.apiBase + this.apiEndpoint, multipartFormContent );

			if( response.IsSuccessStatusCode )
			{
				string responseBody = await response.Content.ReadAsStringAsync();

				ApiResponse apiResponse = JsonConvert.DeserializeObject<ApiResponse>( responseBody );

				return apiResponse;
			}
			else
			{
				// HTTP error

				ApiResponse pingResponse = new ApiResponse();

				pingResponse.msg = $"Hiba: {response.StatusCode}";

				return pingResponse;
			}
		}
	}
}
