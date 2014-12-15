using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

namespace VenueClient
{
    public class GeneralHelper
    {
    }

    public class SpotUser
    {
        [JsonProperty("access_token")]
        public string Token { get; set; }

        [JsonProperty("display_name")]
        public string DisplayName { get; set; }

        [JsonProperty("images")]
        public UserImage Image { get; set; }

    }

    public class UserImage
    {
        [JsonProperty("height")]
        public string Height { get; set; }

        [JsonProperty("url")]
        public string Url { get; set; }

        [JsonProperty("width")]
        public string Width { get; set; }
    }
}