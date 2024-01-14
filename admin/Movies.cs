using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace filmlexikon
{
	public class Movies
	{
		public int movies_id { get; set; }
		public string movies_title { get; set; }
		public string movies_title_original { get; set; }
		public int movies_year { get; set; }
		public Dictionary<string, string> meta { get; set; }

	}
}
