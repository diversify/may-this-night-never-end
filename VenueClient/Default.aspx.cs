using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Configuration;
using System.IO;
using System.Linq;
using System.Net;
using System.Text;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace VenueClient
{
    public partial class ShowVenues : System.Web.UI.Page
    {
        #region Properties
        string defgenres = "Pop,Rock,Piano,Jazz";
        #endregion

        protected void Page_Load(object sender, EventArgs e)
        {
            if (!Page.IsPostBack)
            {
                if (Request.QueryString["code"] != null)
                {
                    string code = Request.QueryString["code"].ToString();

                   // try
                   // { 
                        ProcessUserGeneres(code); 
                    //}
                    //catch (Exception ex)
                    //{
                    //    //todo: log exception message in db
                    //    Response.Redirect(String.Format("{0}{1}", ConfigurationManager.AppSettings["goVenueShowing"].ToString(), defgenres));
                    //}
                    
                   
                }
                else if (Request.QueryString["error"] != null)
                {
                    //todo handle error
                    //Log error in some db                                       
                    Response.Redirect(String.Format("{0}{1}", ConfigurationManager.AppSettings["goVenueShowing"].ToString(), defgenres));
                }
            }           
        }

        private void ProcessUserGeneres(string code)
        {
            string token = GetAccessToken(code);
            List<string> artistsId = GetPreferedArtist(token);

            //comma separate generes
            string csGen = GetPreferedGenres(artistsId);

            //string genres = "Pop,Rock,Piano,Jazz";
            //Response.Redirect(String.Format("http://128.199.48.143/#/moods/{0}", genres));
            string url = ConfigurationManager.AppSettings["goVenueShowing"].ToString();
            Response.Redirect(String.Format("{0}{1}", url, csGen));
        }

        private string GetPreferedGenres(List<string> artistsId)
        {
            //This url would take first 10 songs saved by the user,
            //play with offset and limit to take more
            List<string> userGenres = new List<string>();
            foreach(string artistId in artistsId)
            { 
                var request = (HttpWebRequest)WebRequest.Create(String.Format("http://developer.echonest.com/api/v4/artist/profile?api_key=KPQMXIZM962JAJGPW&id=spotify:artist:{0}&bucket=genre", artistId));
                request.Method = "GET";
                request.ContentType = "application/json";

                var response = (HttpWebResponse)request.GetResponse();
                var responseString = new StreamReader(response.GetResponseStream()).ReadToEnd();

                RootObject tracks = JsonConvert.DeserializeObject<RootObject>(responseString);

                List<Genre> genres;

                try
                {
                    genres = tracks.response.artist.genres;
                }
                catch (Exception ex)
                {
                    genres = new List<Genre>();
                }

                foreach(Genre n in genres)
                {
                    if (!userGenres.Contains(n.name))
                        userGenres.Add(n.name);
                }
            }
            //comma separated genres
            string csGenres = string.Join(",", userGenres.ToArray());
            return csGenres;
        }

        private string GetAccessToken(string code)
        {
            var request = (HttpWebRequest)WebRequest.Create("https://accounts.spotify.com/api/token");
            string stringData = String.Format("grant_type=authorization_code&code={0}&redirect_uri={1}&client_id={2}&client_secret={3}",
                code,HttpUtility.UrlEncode(ConfigurationManager.AppSettings["redirect_uri"].ToString()), ConfigurationManager.AppSettings["clientID"].ToString(), ConfigurationManager.AppSettings["clientS"].ToString());
            byte[] data = Encoding.UTF8.GetBytes(stringData); //enc.GetBytes(stringData);
            
            request.Method = "POST";            
            request.ContentLength = data.Length;
            request.ContentType = "application/x-www-form-urlencoded";

            Stream stream = request.GetRequestStream();
            stream.Write(data, 0, data.Length);
            stream.Close();

            var response = (HttpWebResponse)request.GetResponse();
            var responseString = new StreamReader(response.GetResponseStream()).ReadToEnd();
                       
            SpotUser user = JsonConvert.DeserializeObject<SpotUser>(responseString);
            return user.Token;
        }

        public List<string> GetPreferedArtist(string token)
        {
            //This url would take first 10 songs saved by the user,
            //play with offset and limit to take more
            var request = (HttpWebRequest)WebRequest.Create("https://api.spotify.com/v1/me/tracks?offset=0&limit=10");
            request.Headers.Add("Authorization", String.Format("Bearer {0}", token));
            request.Method = "GET";
            request.ContentType = "application/json";

            var response = (HttpWebResponse)request.GetResponse();
            var responseString = new StreamReader(response.GetResponseStream()).ReadToEnd();

            SavedTrack tracks = JsonConvert.DeserializeObject<SavedTrack>(responseString);

            List<String> allartist = new List<String>();
            List<Artist> artist = new List<Artist>();

            foreach(Item t in tracks.items)
            {
                artist = t.track.artists;
                foreach(Artist a in artist)
                {
                    if (!allartist.Contains(a.id))
                        allartist.Add(a.id); 
                }
            }

            return allartist;
            //List<string> genres = new List<string>(new string[] { "Pop", "Rock", "Piano", "Dance" });
            //return genres;
        }


        //private string GetUser()
        //{
        //    var request = (HttpWebRequest)WebRequest.Create("https://api.spotify.com/v1/me");
        //    request.Headers.Add("Authorization","Bearer BQB02cqWRp21-PDRkoWxS-Eziyn6jWnYZjOHgRHo20YrRzkCnFKcg_LfJ8Vfb53PFpms0Fx1vSSG41yqXlUUsQiDQlWZSs55Btn20FcsU2_JFwTxRZeO8JypnuIKyLdz2UooMEKMADEgPNi8t5ARqgGV_fvY");
        //    request.Method = "GET";
        //    request.ContentType = "application/json";

        //    var response = (HttpWebResponse)request.GetResponse();
        //    var responseString = new StreamReader(response.GetResponseStream()).ReadToEnd();

        //    return JsonConvert.SerializeObject(responseString, Formatting.Indented);
        //}
    }
}