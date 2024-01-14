using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Newtonsoft.Json;

namespace filmlexikon
{
	public partial class Form1 : Form
	{
		APIclient APIclient = new APIclient();

		public Form1()
		{
			// konstruktor

			InitializeComponent();

			// inditaskor az elso tabon levo filmlista betoltese
			this.MoviesList();

			// a plakat ImageLocation parametere alapertelmezetten ures legyen, ne null
			moviesFormPosterPreview.ImageLocation = "";

			// es a szemely foto is
			personsFormPhoto.ImageLocation = "";
		}

		/* ALTALANOS MUVELETEK */

		private int previousTabIndex = 0;

		private void tabControl1_Selecting( object sender, TabControlCancelEventArgs e )
		{
			// tabok kozti valtas

			if( tabControl1.SelectedIndex != 1 && previousTabIndex == 1 && moviesFormTitle.Text != "" )
			{
				// filmek formrol valtunk masik tabra, de a form nem ures (a film cime), azaz felbehagytuk az uj film felvitelt vagy modositast
				DialogResult result = MessageBox.Show( "Nem mentett adatok lehetnek az űrlapon. Biztosan továbblépsz?", "Figyelem", MessageBoxButtons.YesNo, MessageBoxIcon.Warning );

				if( result == DialogResult.No )
				{
					// megse valtunk tabot
					tabControl1.SelectedIndex = previousTabIndex;
					return;
				}
			}

			// megis valtunk tabot, ekkor alaphelyzetbe allitjuk a formot
			this.resetMoviesForm();

			previousTabIndex = tabControl1.SelectedIndex;

			if( tabControl1.SelectedIndex == 0 )
			{
				// a filmek tabra valtaskor betoltjuk/frissitjuk a filmek listajat
				this.MoviesList();
			}
			if( tabControl1.SelectedIndex == 2 )
			{
				// a szemelyek tabra valtaskor betoltjuk/frissitjuk a szemelyek listajat
				this.PersonsList();
			}

		}

		/* ELSO TAB, FILMEK LISTAJAN LEVO MUVELETEK */

		private async void MoviesList( string search = "" )
		{
			// filmek listajanak letoltese es kiirasa az elso tabon levo dataGridView -ba

			// amig varjuk a valaszt, addig az elso tab-on levo ket gombot inaktivra allitjuk, hogy a felhasznalo addig ne nyomkodja
			moviesBtnList.Enabled = false;
			moviesBtnSearch.Enabled = false;

			// egy szotar, amibe belekerulnek az API keres parameterei
			Dictionary<string, string> requestParams = new Dictionary<string, string>();

			// a kert muvelet a filmek listaja
			requestParams[ "action" ] = "list_movies";

			// opcionalisan keresesi kifejezes, ha ez ures, akkor a szerver nem veszi figyelembe, minden filmet felsorol
			requestParams[ "search" ] = search;

			// Task az API hivashoz
			Task<APIclient.ApiResponse> task = APIclient.callMultipart( requestParams );

			// API hivas es megvarjuk a valaszt
			APIclient.ApiResponse apiResponse = await task;

			if( apiResponse.status == "ok" )
			{
				// a valasz "ok", azaz sikeres a lekeres

				// kiuritjuk a kezdolapi dataGridView-t
				dataGridView1.Columns.Clear();

				// a movies tartalmazza a filmek listajat
				dataGridView1.DataSource = apiResponse.movies;

				// egy foreach vegigmegy az elemein, mert a dataGridView-ba csak a fobb adatokat irjuk ki
				foreach( var prop in typeof(Movies).GetProperties() )
				{
					// a listaban csak ezeket az adatokat jelenitjuk meg, a tobbi oszlopot toroljuk, a listaba nem szukseges, nem is nagyon ferne el minden
					if(
						prop.Name != "movies_id" &&
						prop.Name != "movies_title" &&
						prop.Name != "movies_title_original" &&
						prop.Name != "movies_year" &&
						prop.Name != "movies_director"
					)
					{
						dataGridView1.Columns.Remove(prop.Name);
					}
				}

				// megjott a valasz, ujra hasznalhato a ket gomb az elso tab-on
				moviesBtnList.Enabled = true;
				moviesBtnSearch.Enabled = true;
			}
			else
			{
				// a status nem "ok", valami miatt sikertelen a lekeres, kiirjuk a valasz uzenetet
				MessageBox.Show( apiResponse.msg, "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Error );
			}
		}

		private void moviesBtnList_Click( object sender, EventArgs e )
		{
			// elso tab-on a Filmlista frissitese gombra kattintas, ujratoltjuk a listat

			// uritjuk a keresomezot, azzal csak akkor foglalkozunk, ha a kereses gombra kattintunk
			moviesInputSearch.Text = "";

			// API hivas
			this.MoviesList();
		}

		private void moviesBtnSearch_Click( object sender, EventArgs e )
		{
			// elso tab-on a Kereses gombra kattintas, ujratoltjuk a listat

			// keresomezobe irt kifejezes
			string search = moviesInputSearch.Text.ToString();

			// API hivas
			this.MoviesList( search );
		}

		private void moviesInputSearch_KeyDown( object sender, KeyEventArgs e )
		{
			// a keresomezoben entert nyomva is inditsa a keresest

			if( e.KeyCode == Keys.Return )
			{
				string search = moviesInputSearch.Text.ToString();

				this.MoviesList( search );

				// tovabbi esemenyek megallitasa
				e.SuppressKeyPress = true;
			}
		}

		private void dataGridView1_CellClick( object sender, DataGridViewCellEventArgs e )
		{
			// elso tab, dataGridView, ha egy sorra kattintunk egyet, akkor jeloljuk ki a sort
			dataGridView1.CurrentRow.Selected = true;
		}

		private async void dataGridView1_CellDoubleClick( object sender, DataGridViewCellEventArgs e )
		{
			// elso tab, dataGridView, dupla kattintasra szerkeszthetjuk a film adatait

			if( e.RowIndex >= 0 )
			{
				// kijeloljuk a sort, amire kattintottunk
				dataGridView1.CurrentRow.Selected = true;

				// atvaltunk a filmek form-ra
				tabControl1.SelectedIndex = 1;

				// az adott film azonositoja
				string movies_id = dataGridView1.Rows[ e.RowIndex ].Cells[ "movies_id" ].Value.ToString();

				// beirjuk a film azonositojat, ez kell a film modositasahoz
				moviesFormId.Text = movies_id;

				// lekerjuk a film adatait

				// szotar a lekeresi parameterekhez
				Dictionary<string, string> requestParams = new Dictionary<string, string>();

				// a muvelet egy film adatainak lekerese
				requestParams[ "action" ] = "get_movie";

				// a film egyedi azonositoja
				requestParams[ "movies_id" ] = movies_id;

				// Task es varunk a valaszra
				Task<APIclient.ApiResponse> task = APIclient.callMultipart(requestParams);

				APIclient.ApiResponse apiResponse = await task;

				// egy egy elemu objektum a valasz, benne a film adataival
				List<Movies> movie = apiResponse.movies;

				foreach( var m in movie )
				{
					// kitoltjuk a formot a film adataival
					
					moviesFormTitle.Text = m.movies_title;
					moviesFormTitleOriginal.Text = m.movies_title_original;

					if( m.meta.ContainsKey( "countries" ) )
					{
						moviesFormCountry.Text = m.meta[ "countries" ];
					}

					if( m.meta.ContainsKey( "categories" ) )
					{
						moviesFormCategory.Text = m.meta[ "categories" ];
					}

					moviesFormYear.Text = m.movies_year.ToString();

					Dictionary<string, string> director = JsonConvert.DeserializeObject<Dictionary<string, string>>( m.meta[ "director" ] );

					moviesFormDirector.Text = director[ "persons_name" ];

					if( m.meta.ContainsKey( "actors" ) )
					{
						moviesFormActors.Text = m.meta[ "actors" ];
					}
					
					if ( m.meta.ContainsKey( "plot" ) )
					{
						moviesFormPlot.Text = m.meta[ "plot" ];
					}

					if( m.meta.ContainsKey( "poster_url" ) && m.meta[ "poster_url" ] != "" )
					{
						moviesFormPosterPreview.ImageLocation = m.meta[ "poster_url" ];
					}

				}

				// modositjuk a mentes gomb szoveget
				moviesFormBtnSend.Text = "Módosítások mentése";

			}

		}

		private async void dataGridView1_KeyDown( object sender, KeyEventArgs e )
		{
			// elso tab, dataGridView, keydown esemeny, ha delete-et nyomunk, azzal torolhetunk egy filmet

			if( e.KeyCode == Keys.Delete )
			{
				// van-e kijelolve sor
				if( dataGridView1.SelectedRows.Count > 0 )
				{
					// a kijelolt sor
					DataGridViewRow selectedRow = dataGridView1.SelectedRows[0];

					// ez mindig int, de string-kent adjuk at a requestParams szotarnak, ezert string a tipusa
					string movies_id = selectedRow.Cells[ "movies_id" ].Value.ToString();

					// a film cime itt csak azert kell, hogy meg tudjuk kerdezni a felhasznalot, torli-e az XY cimu filmet
					string movies_title = selectedRow.Cells[ "movies_title" ].Value.ToString();

					// megerositest kerunk
					DialogResult result = MessageBox.Show( $"Biztosan törölni szeretnéd a(z) {movies_title} című filmet?", "Törlés megerősítése", MessageBoxButtons.YesNo, MessageBoxIcon.Warning );

					// ha igen, akkor API hivas torles muvelettel
					if( result == DialogResult.Yes )
					{
						Dictionary<string, string> requestParams = new Dictionary<string, string>();

						requestParams[ "action" ] = "delete_movie";

						requestParams[ "movies_id" ] = movies_id;

						Task<APIclient.ApiResponse> task = APIclient.callMultipart( requestParams );

						APIclient.ApiResponse apiResponse = await task;

						if( apiResponse.status == "ok" )
						{
							this.MoviesList();

							MessageBox.Show( apiResponse.msg, "Sikeres művelet", MessageBoxButtons.OK, MessageBoxIcon.Information );
						}
						else
						{
							MessageBox.Show( apiResponse.msg, "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Error );
						}
					}
				}
			}

		}
		
		/* FILMEK FORM */

		private async void moviesFormBtnSend_Click( object sender, EventArgs e )
		{
			// filmek form mentese

			// gombok disabled, amig varunk a valaszra, ne nyomkodja a felhasznalo
			moviesFormBtnSend.Text = "Kérlek várj ...";
			moviesFormBtnSend.Enabled = false;
			moviesFormBtnReset.Enabled = false;
			moviesFormBtnPoster.Enabled = false;
			moviesFormBtnRemovePoster.Enabled = false;

			Dictionary<string, string> requestParams = new Dictionary<string, string>();

			// az action hozzaadasnal es modositasnal is ugyan az, az API szerver a movies_id alapjan tudja, hogy add vagy edit, ures == add, nem ures == edit

			requestParams[ "action" ] = "save_movie";
			requestParams[ "movies_id" ] = moviesFormId.Text;
			requestParams[ "movies_title" ] = moviesFormTitle.Text;
			requestParams[ "movies_title_original" ] = moviesFormTitleOriginal.Text;
			requestParams[ "movies_country" ] = moviesFormCountry.Text;
			requestParams[ "movies_category" ] = moviesFormCategory.Text; 
			requestParams[ "movies_year" ] = moviesFormYear.Text;
			requestParams[ "movies_director" ] = moviesFormDirector.Text;
			requestParams[ "movies_actors" ] = moviesFormActors.Text;
			requestParams[ "movies_plot" ] = moviesFormPlot.Text;
			requestParams[ "poster" ] = moviesFormPosterPreview.ImageLocation;
			requestParams[ "poster_url" ] = moviesFormPosterURL.Text;
			requestParams[ "poster_delete" ] = moviesFormDeletePoster.Text;

			Task<APIclient.ApiResponse> task = APIclient.callMultipart( requestParams );

			APIclient.ApiResponse apiResponse = await task;

			if( apiResponse.status == "ok" )
			{
				if( moviesFormId.Text == "" )
				{
					// ha uj filmet adtunk hozza, akkor uritjuk a formot, mehet a kovetkezo film
					this.resetMoviesForm();
				}
			}

			// sikeres API valasz utan visszairjuk a kuldes gomb szoveget
			if( moviesFormId.Text == "" )
			{
				moviesFormBtnSend.Text = "Film hozzáadása";
			}
			else
			{
				moviesFormBtnSend.Text = "Módosítások mentése";
			}

			moviesFormTitle.Focus();

			// ujra nyomkodhatjuk a gombokat
			moviesFormBtnSend.Enabled = true;
			moviesFormBtnReset.Enabled = true;
			moviesFormBtnPoster.Enabled = true;
			moviesFormBtnRemovePoster.Enabled = true;

			// kiirjuk a valaszt
			if( apiResponse.status == "ok" )
			{
				MessageBox.Show( apiResponse.msg, "Sikeres művelet", MessageBoxButtons.OK, MessageBoxIcon.Information );
			}
			else
			{
				MessageBox.Show( apiResponse.msg, "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Warning );
			}
		}

		private void moviesFormBtnReset_Click( object sender, EventArgs e )
		{
			// filmek form alaphelyzetbe allitasa

			DialogResult result = MessageBox.Show( $"Biztosan kiüríted a formot?", "Megerősítés", MessageBoxButtons.YesNo, MessageBoxIcon.Warning );

			if( result == DialogResult.Yes )
			{
				this.resetMoviesForm();

				moviesFormTitle.Focus();
			}
		}

		private void moviesFormBtnPoster_Click( object sender, EventArgs e )
		{
			// plakat tallozas gombra kattintas
			
			OpenFileDialog openFileDialog1 = new OpenFileDialog
			{
				InitialDirectory = @"C:\",
				Title = "Film plakát feltöltése",
				CheckFileExists = true,
				CheckPathExists = true,
				DefaultExt = "jpg",
				Filter = "image files (*.jpg)|*.jpg",
				FilterIndex = 2,
				RestoreDirectory = true,
				ReadOnlyChecked = true,
				ShowReadOnly = true
			};

			if( openFileDialog1.ShowDialog() == DialogResult.OK )
			{
				// megjelenitjuk a tallozott kepet
				moviesFormPosterPreview.ImageLocation = openFileDialog1.FileName.ToString();
				moviesFormPosterURL.Text = "";
			}
		}

		private void moviesFormPosterURL_TextChanged(object sender, EventArgs e)
		{
			// plakat letoltese URL-bol, ez esetben a plakat URL-t kuldjuk a szervernek, ami letolti onnan a kepet
			string url = moviesFormPosterURL.Text;

			if( url != "" )
			{
				if(
					url.StartsWith( "http://" ) || 
					url.StartsWith( "https://" ) && 
					url.EndsWith( ".jpg", StringComparison.OrdinalIgnoreCase )
			){
					moviesFormPosterPreview.ImageLocation = url.ToString();
				}
				else
				{
					MessageBox.Show( "Hibás URL, a plakát helyes URL-je http:// vagy https:// -sel kezdődik és csak JPG lehet, tehát .jpg -vel végződik", "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Warning );
				}
			}
		}

		private void moviesFormBtnRemovePoster_Click( object sender, EventArgs e )
		{
			// szemely foto torlese

			DialogResult result = MessageBox.Show( $"Biztosan törlöd a plakátot?", "Megerősítés", MessageBoxButtons.YesNo, MessageBoxIcon.Warning );

			if( result == DialogResult.Yes )
			{
				moviesFormPosterPreview.ImageLocation = "";
				moviesFormPosterURL.Text = "";
				moviesFormDeletePoster.Text = "ok";
			}
		}

		private void resetMoviesForm()
		{
			// alaphelyzetbe allitjuk a filmek form-ot

			moviesFormId.Text = "";
			moviesFormTitle.Text = "";
			moviesFormTitleOriginal.Text = "";
			moviesFormYear.Text = "";
			moviesFormCountry.Text = "";
			moviesFormCategory.Text = "";
			moviesFormDirector.Text = "";
			moviesFormActors.Text = "";
			moviesFormPlot.Text = "";
			moviesFormPosterURL.Text = "";
			moviesFormPosterPreview.ImageLocation = "";
			moviesFormDeletePoster.Text = "";
			moviesFormBtnSend.Text = "Film hozzáadása";
		}   
		
		/* HARMADIK TAB SZEMELYEK */

		private async void PersonsList( string search = "" )
		{
			// a MoviesList-hez hasonloan ez a Szemelyek tab-on felsorolja az osszes szemelyt

			Dictionary<string, string> requestParams = new Dictionary<string, string>();

			// muvelet: a szemelyek listaja
			requestParams[ "action" ] = "list_persons";

			// opcionalis keresesi kifejezes
			requestParams[ "search" ] = search;

			// API hivas
			Task<APIclient.ApiResponse> task = APIclient.callMultipart(requestParams);

			APIclient.ApiResponse apiResponse = await task;

			if( apiResponse.status == "ok" )
			{
				// sikeres valasz, kiuritjuk a dataGridView-t
				dataGridView2.Columns.Clear();

				// felsoroljuk a szemelyeket
				dataGridView2.DataSource = apiResponse.persons;

				// de nem minden adatukat
				foreach( var prop in typeof( Persons ).GetProperties() )
				{
					// a listaban csak ezeket az adatokat jelenitjuk meg, a tobbi oszlopot toroljuk
					if(
						prop.Name != "persons_id" &&
						prop.Name != "persons_name" &&
						prop.Name != "movies_count"
					)
					{
						dataGridView2.Columns.Remove(prop.Name);
					}
				}
			}
			else
			{
				// sikertelen lekeres, az msg tartalmazza a hibauzenetet
				MessageBox.Show( apiResponse.msg, "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Error );
			}
		}

		private void personsFormEnable()
		{
			// a szemelyek form alapertelmezetten disabled, mert csak modositasra hasznalhato, uj szemelyt a filmjevel egyutt viszunk fel
			
			personsFormInputName.Enabled = true;
			personsFormInputBirth.Enabled = true;
			personsFormInputCountry.Enabled = true;
			personsFormBtnSend.Enabled = true;
			personsFormBtnCancel.Enabled = true;
			personsFormBtnPhoto.Enabled = true;
			personsFormBtnPhotoDelete.Enabled = true;
			personsFormInputPhotoURL.Enabled = true;
		}

		private void personsFormDisable()
		{
			personsFormInputName.Text = "";
			personsFormInputName.Enabled = false;
			personsFormInputBirth.Text = "";
			personsFormInputBirth.Enabled = false;
			personsFormInputCountry.Text = "";
			personsFormInputCountry.Enabled = false;
			personsFormBtnSend.Enabled = false;
			personsFormBtnCancel.Enabled = false;
			personsFormPhoto.ImageLocation = "";
			personsFormBtnPhoto.Enabled = false;
			personsFormBtnPhotoDelete.Enabled = false;
			personsFormInputPhotoURL.Text = "";
			personsFormInputPhotoURL.Enabled = false;
			personsFormDeletePhoto.Text = "";
			personsFormId.Text = "";
		}

		private void personsBtnSearch_Click( object sender, EventArgs e )
		{
			// szemely keresese
			this.PersonsList( personsInputSearch.Text );
		}

		private async void dataGridView2_CellClick( object sender, DataGridViewCellEventArgs e )
		{
			// szemely listaban egy sorra kattintva az adott szemely adatait betoltjuk a formba, hogy modosithassuk az adatait, fotojat
			if( e.RowIndex >= 0 )
			{
				// kijeloljuk a sort, amire kattintottunk
				dataGridView2.CurrentRow.Selected = true;

				// szemely egyedi azonosito
				string persons_id = dataGridView2.Rows[ e.RowIndex ].Cells[ "persons_id" ].Value.ToString();

				this.personsFormDisable();
				this.personsFormEnable();

				// API hivas parameterek, majd API hivas
				Dictionary<string, string> requestParams = new Dictionary<string, string>();

				requestParams[ "action" ] = "get_person";
				requestParams[ "persons_id" ] = persons_id;

				Task<APIclient.ApiResponse> task = APIclient.callMultipart( requestParams );

				APIclient.ApiResponse apiResponse = await task;

				// a persons egy elemu tomb tartalmazza a szemely adatait
				List<Persons> person = apiResponse.persons;

				// kitoltjuk a formot az adataival
				foreach( var p in person )
				{
					personsFormInputName.Text = p.persons_name;

					personsFormId.Text = p.persons_id.ToString();

					if( p.meta.ContainsKey( "persons_birth" ) )
					{
						personsFormInputBirth.Text = p.meta[ "persons_birth" ];
					}
					else
					{
						personsFormInputBirth.Text = "";
					}

					if( p.meta.ContainsKey( "persons_country" ) )
					{
						personsFormInputCountry.Text = p.meta[ "persons_country" ];
					}
					else
					{
						personsFormInputCountry.Text = "";
					}

					if( p.meta.ContainsKey( "photo_url" ) )
					{
						personsFormPhoto.ImageLocation = p.meta[ "photo_url" ];
					}
					else 
					{
						personsFormPhoto.ImageLocation = "";
					}
				}
			}
		}

		private async void personsFormBtnSend_Click(object sender, EventArgs e)
		{
			// szemely form kuldese

			personsFormBtnSend.Text = "Kérlek várj ...";
			personsFormBtnSend.Enabled = false;
			personsFormBtnCancel.Enabled = false;
			personsFormBtnPhoto.Enabled = false;
			personsFormBtnPhotoDelete.Enabled = false;
			personsFormInputPhotoURL.Enabled = false;

			// API parameterek es a szemely adatai, fotoja
			Dictionary<string, string> requestParams = new Dictionary<string, string>();

			requestParams[ "action" ] = "save_person";
			requestParams[ "persons_id" ] = personsFormId.Text;
			requestParams[ "persons_name" ] = personsFormInputName.Text;
			requestParams[ "persons_birth" ] = personsFormInputBirth.Text;
			requestParams[ "persons_country" ] = personsFormInputCountry.Text;
			requestParams[ "photo" ] = personsFormPhoto.ImageLocation;
			requestParams[ "photo_url" ] = personsFormInputPhotoURL.Text;
			requestParams[ "photo_delete" ] = personsFormDeletePhoto.Text;

			Task<APIclient.ApiResponse> task = APIclient.callMultipart(requestParams);

			APIclient.ApiResponse apiResponse = await task;

			personsFormBtnSend.Text = "Módosítások mentése";
			personsFormBtnSend.Enabled = true;
			personsFormBtnCancel.Enabled = true;
			personsFormBtnPhoto.Enabled = true;
			personsFormBtnPhotoDelete.Enabled = true;
			personsFormInputPhotoURL.Enabled = true;

			// kiirjuk a valasz uzenetet
			if( apiResponse.status == "ok" )
			{
				MessageBox.Show( apiResponse.msg, "Sikeres művelet", MessageBoxButtons.OK, MessageBoxIcon.Information );
			}
			else
			{
				MessageBox.Show( apiResponse.msg, "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Error );
			}
		}

		private void personsFormBtnCancel_Click(object sender, EventArgs e)
		{
			// szemelyek form reset
			this.personsFormDisable();
		}

		private void personsFormBtnPhoto_Click(object sender, EventArgs e)
		{
			// szemely foto tallozasa a film plakathoz hasonloan

			OpenFileDialog openFileDialog1 = new OpenFileDialog
			{
				InitialDirectory = @"C:\",
				Title = "Személy fotó feltöltése",
				CheckFileExists = true,
				CheckPathExists = true,
				DefaultExt = "jpg",
				Filter = "image files (*.jpg)|*.jpg",
				FilterIndex = 2,
				RestoreDirectory = true,
				ReadOnlyChecked = true,
				ShowReadOnly = true
			};

			if( openFileDialog1.ShowDialog() == DialogResult.OK )
			{
				personsFormPhoto.ImageLocation = openFileDialog1.FileName.ToString();
			}
		}

		private void personsFormInputPhotoURL_TextChanged(object sender, EventArgs e)
		{
			// szemely foto letoltese URL-bol, hasonloan a filmek plakatjahoz

			string url = personsFormInputPhotoURL.Text;

			if( url != "" )
			{
				if(
					url.StartsWith( "http://" ) || 
					url.StartsWith( "https://" ) && 
					url.EndsWith( ".jpg", StringComparison.OrdinalIgnoreCase )
				){
					personsFormPhoto.ImageLocation = url.ToString();
				}
				else
				{
					MessageBox.Show( "Hibás URL, a fotó helyes URL-je http:// vagy https:// -sel kezdődik és csak JPG lehet, tehát .jpg -vel végződik", "Hiba", MessageBoxButtons.OK, MessageBoxIcon.Warning );
				}
			}
		}

		private void personsFormBtnPhotoDelete_Click(object sender, EventArgs e)
		{
			personsFormDeletePhoto.Text = "ok";
			personsFormPhoto.ImageLocation = "";
			personsFormInputPhotoURL.Text = "";
		}
	}
}
