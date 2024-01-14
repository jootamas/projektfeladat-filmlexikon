using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace filmlexikon
{
	public class Persons
	{
		public int persons_id { get; set; }
		public string persons_name { get; set; }
		public Dictionary<string, string> meta { get; set; }
		public int movies_count { get; set; }
	}
}

