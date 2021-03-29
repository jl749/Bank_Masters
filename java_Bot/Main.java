import java.sql.*; 

import org.json.simple.JSONArray; 
import org.json.simple.JSONObject; 
import org.json.simple.parser.*; 
import java.net.URL;
import java.net.URLConnection;
import java.io.InputStreamReader; 
import java.io.InputStream;
import java.util.*;

import java.math.BigInteger; 
import java.security.MessageDigest; 
import java.security.NoSuchAlgorithmException; 

import java.text.*;

import java.util.regex.Pattern; 
/**
 * java bot for Bank Masters
 *
 * @author (jl749)
 */
public class Main
{
    ArrayList<Double> buyRecord=new ArrayList<>();
    ArrayList<Double> volumeRecord=new ArrayList<>();
    ArrayList<Double> priceRecord=new ArrayList<>();
    
    final String url = "jdbc:mysql://dragon.kent.ac.uk:3306/jl749"; //dragon.kent.ac.uk
    final String user = "jl749"; 
    final String pass = "p@ssword1";
    String username=null;
    final Set<String> shares=new HashSet<>(Arrays.asList("msft","aapl","csco","ibm","nvda","amd","amzn","nflx","intc","goog"));
    /**
     * enter website ID and Password
     */
    public Main(String username,String password)
    {
        Connection con=null;
        //Statement stat=null;
        try{
            Class.forName("com.mysql.cj.jdbc.Driver");
            DriverManager.registerDriver(new oracle.jdbc.OracleDriver()); 
            
            con=DriverManager.getConnection(url,user,pass);
            System.out.println("[mysql connection]....");
            //stat=con.createStatement();
            PreparedStatement pstmt = con.prepareStatement("select username,password from User where username=? AND password=?");
            pstmt.setString(1, "bob");
            pstmt.setString(2, sha1(password));
            ResultSet rs=pstmt.executeQuery();
            if(!rs.next()){
                System.out.println("User doesnt exist\n Check your username and password");
                return;
            }
            //rs.beforeFirst();//reset pointer
            String name=rs.getString("username");
            System.out.println("Connected successfully \nWelcome "+name);
            //stat.close();
            con.close(); 
        }catch(Exception e){
            System.err.println(e);
        }
        this.username=username;
    }
    public void buyAt(String symbol,double priceW,int amount){
        System.out.println("buyAt "+priceW+"\nfunction called\nmacro will start soon...");
        int count=1;
        if(username==null){
            System.out.println("User doesnt exist\n Check your username and password");
            return;
        }else if(!chkSymbol(symbol)){
            System.out.println("Check symbol again");
            return;
        }
        
        //time
        SimpleDateFormat format = new SimpleDateFormat("HH:mm");
        TimeZone etTimeZone = TimeZone.getTimeZone("America/New_York");
        format.setTimeZone(etTimeZone);
        Calendar currentTime = Calendar.getInstance();
        String time=format.format(currentTime.getTimeInMillis());
        try {
            while (chkTime(time)) {
                System.out.println("------------------"+count+++"------------------");
                double price=getPrice(symbol);
                if(priceW>=getPrice(symbol)){
                    buy(symbol,price,amount);
                    break;
                }
                currentTime = Calendar.getInstance();
                time=format.format(currentTime.getTimeInMillis());
                Thread.sleep(5 * 60000);//every 5min
            }
            if(!chkTime(time)){System.out.println("market closed");}
            System.out.println("macro terminated");
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
    public void sellAt(String symbol,double priceW,int amount){
        System.out.println("sellAt "+priceW+"\nfunction called\nmacro will start soon...");
        int count=1;
        if(username==null){
            System.out.println("User doesnt exist\n Check your username and password");
            return;
        }else if(!chkSymbol(symbol)){
            System.out.println("Check symbol again");
            return;
        }
        
        //time
        SimpleDateFormat format = new SimpleDateFormat("HH:mm");
        TimeZone etTimeZone = TimeZone.getTimeZone("America/New_York");
        format.setTimeZone(etTimeZone);
        Calendar currentTime = Calendar.getInstance();
        String time=format.format(currentTime.getTimeInMillis());
        try {
            while (chkTime(time)) {
                System.out.println("------------------"+count+++"------------------");
                double price=getPrice(symbol);
                if(priceW<=getPrice(symbol)){
                    sell(symbol,price,amount);
                    break;
                }
                currentTime = Calendar.getInstance();
                time=format.format(currentTime.getTimeInMillis());
                Thread.sleep(5 * 60000);//every 5min
            }
            if(!chkTime(time)){System.out.println("market closed");}
            System.out.println("macro terminated");
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
    
    /**
     * profitrate: sell shares if price goes above certain percentage from the value it bought at
     * ex) profitrate=1.015  =>  1.5% (recommanded: 0.3%)
     * amount: amount of shares traded at each time
     */
    public void run(String symbol,double profitRate,int amount){
        System.out.println("run function called macro will start soon...");
        int count=1;
        buyRecord.clear();
        volumeRecord.clear();
        priceRecord.clear();
        if(username==null){
            System.out.println("User doesnt exist\n Check your username and password");
            return;
        }else if(!chkSymbol(symbol)){
            System.out.println("Check symbol again");
            return;
        }
        
        //time
        SimpleDateFormat format = new SimpleDateFormat("HH:mm");
        TimeZone etTimeZone = TimeZone.getTimeZone("America/New_York");
        format.setTimeZone(etTimeZone);
        Calendar currentTime = Calendar.getInstance();
        String time=format.format(currentTime.getTimeInMillis());
        try {
            while (chkTime(time)) {
                //update time
                currentTime = Calendar.getInstance();
                time=format.format(currentTime.getTimeInMillis());
                
                System.out.println("------------------"+count+++"------------------");
                double price=getPrice(symbol);
                double volume=getVolume(symbol);
                if(volumeRecord.isEmpty()){
                    buy(symbol,price,amount);
                    System.out.println("Current Time: "+time);
                }
                volumeRecord.add(volume);
                priceRecord.add(price);
                double avgV=calculateAverage(volumeRecord);
                double avgP=calculateAverage(priceRecord);
                if(avgP*0.95>=price){
                    buy(symbol,price,amount);
                    System.out.println("Current Time: "+time);
                }
                if(avgV*1.15<=volumeRecord.get(volumeRecord.size()-1)){
                    buy(symbol,price,amount);
                    System.out.println("Current Time: "+time);
                }
                for(double i:buyRecord){
                    if(i*profitRate<=price){
                        sell(symbol,price,amount);
                        buyRecord.remove(buyRecord.indexOf(i));
                        System.out.println("Current Time: "+time);
                    }
                }
                Thread.sleep(5 * 60000);//every 5min
            }
            System.out.println("market closed");
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
    private void buy(String symbol,double price,int amount){
        if(getBalance()<price*amount){
            System.out.println("Not enough balance\nCall balance function to check how much you owned");
            return;
        }
        Connection con=null;
        String sql="insert into Transaction("+ " Amount,"+ " TradedAt,"+ " ActivityTime,"+ " Username,"+ " TickerID )  values(?,?,?,?,?)";
        try{
            //time
            SimpleDateFormat format = new SimpleDateFormat("yyy-MM-dd HH:mm:ss");
            Calendar currentTime = Calendar.getInstance();
            String time=format.format(currentTime.getTimeInMillis());
            con = DriverManager.getConnection(url,user,pass);
            
            PreparedStatement pstmt = con.prepareStatement(sql);
            pstmt = con.prepareStatement(sql);
            pstmt.setString(1, Integer.toString(amount));
            pstmt.setString(2, Double.toString(price));
            pstmt.setString(3, time);
            pstmt.setString(4, username);
            pstmt.setString(5, symbol.toUpperCase());
            int row=pstmt.executeUpdate();//rows affected
            System.out.println(row+" row affected");;
            con.close(); 
            updateBalance(-1*(price*amount));
            buyRecord.add(price);
            System.out.println("Bought "+amount+" "+symbol+" for "+price);
        }catch(Exception e){ 
            System.err.println(e); 
        }
    }
    private void sell(String symbol,double price,int amount){
        if(getAmount(symbol)<amount){
            System.out.println("Not enough shares\nCall sharesheld function to check amount you owned");
            return;
        }
        
        Connection con=null;
        String sql="insert into Transaction("+ " Amount,"+ " TradedAt,"+ " ActivityTime,"+ " Username,"+ " TickerID )  values(?,?,?,?,?)";
        try{
            //time
            SimpleDateFormat format = new SimpleDateFormat("yyy-MM-dd HH:mm:ss");
            Calendar currentTime = Calendar.getInstance();
            String time=format.format(currentTime.getTimeInMillis());
            con = DriverManager.getConnection(url,user,pass);
            
            PreparedStatement pstmt = con.prepareStatement(sql);
            pstmt = con.prepareStatement(sql);
            pstmt.setString(1, Integer.toString(amount*-1));
            pstmt.setString(2, Double.toString(price));
            pstmt.setString(3, time);
            pstmt.setString(4, username);
            pstmt.setString(5, symbol.toUpperCase());
            int row=pstmt.executeUpdate();//rows affected
            System.out.println(row+" row affected");
            con.close(); 
            updateBalance(price*amount);
            System.out.println("Sold "+amount+" "+symbol+" for "+price);
        }catch(Exception e){ 
            System.err.println(e); 
        }
    }
    private boolean chkTime(String time){
        String[] part=time.split(":");
        int h=Integer.parseInt(part[0]);
        int m=Integer.parseInt(part[1]);
        if(h<16 && h>=9){
            if(h==9 && m<30){return false;}
            return true;
        }
        return false;
    }
    private double calculateAverage(List<Double> volumeRecord){
        double sum=0;
        if(!volumeRecord.isEmpty()){
            if(volumeRecord.size()>=10){
                for(int i=0;i<10;i++){
                    sum+=volumeRecord.get(volumeRecord.size()-1-i);
                }
                return sum/10;
            }
            for(Double d: volumeRecord){
                sum+=d;
            }
            return sum/volumeRecord.size();
        }
        return sum;
    }
    
    private double getPrice(String symbol){
        double price=-1;
        try{
            String sURL="https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol="+symbol+"&apikey=HWRLWXA50QHIR207";
            URL url=new URL(sURL);
            URLConnection request=url.openConnection();
            request.connect();
            
            Object obj=new JSONParser().parse(new InputStreamReader((InputStream) request.getContent()));
            JSONObject jobj=(JSONObject)obj;
            JSONObject info=(JSONObject)jobj.get("Global Quote");
            String tmp=(String)info.get("05. price");
            price=Double.parseDouble(tmp);
        }catch(Exception e){
            e.printStackTrace();
        }
        return price;
    }
    private double getVolume(String symbol){
        double volume=-1;
        try{
            String sURL="https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol="+symbol+"&apikey=HWRLWXA50QHIR207";
            URL url=new URL(sURL);
            URLConnection request=url.openConnection();
            request.connect();
            
            Object obj=new JSONParser().parse(new InputStreamReader((InputStream) request.getContent()));
            JSONObject jobj=(JSONObject)obj;
            JSONObject info=(JSONObject)jobj.get("Global Quote");
            String tmp=(String)info.get("06. volume");
            volume=Double.parseDouble(tmp);
        }catch(Exception e){
            e.printStackTrace();
        }
        return volume;
    }
    private int getAmount(String symbol){
        int amount=-1;
        Connection con=null;
        try{
            con=DriverManager.getConnection(url,user,pass);
            PreparedStatement pstmt = con.prepareStatement("select sum(amount) as amount from Transaction where username=? AND tickerid=? group by username,tickerid");
            pstmt.setString(1, username);
            pstmt.setString(2, symbol);
            ResultSet rs=pstmt.executeQuery();

            rs.next();
            amount=Integer.parseInt(rs.getString("amount"));
            con.close();
        }catch(Exception e){
            System.err.println(e);
        }
        return amount;
    }
    private double getBalance(){
        double balance=-1;
        //NumberFormat formatter = new DecimalFormat("#0.00");
        Connection con=null;
        try{
            con=DriverManager.getConnection(url,user,pass);
            PreparedStatement pstmt = con.prepareStatement("select balance from User where username=?");
            pstmt.setString(1, username);
            ResultSet rs=pstmt.executeQuery();
            
            rs.next();
            balance=Double.parseDouble(rs.getString("balance"));
            con.close(); 
        }catch(Exception e){
            System.err.println(e);
        }
        return balance;
    }
    private void updateBalance(double val){
        double balance=getBalance();
        if(balance==-1){
            return;
        }
        //NumberFormat formatter = new DecimalFormat("#0.00");
        Connection con=null;
        String sql="update User set balance=? where username=?";
        try{
            con = DriverManager.getConnection(url,user,pass);
            
            PreparedStatement pstmt = con.prepareStatement(sql);
            pstmt = con.prepareStatement(sql);
            pstmt.setDouble(1, balance+val);
            pstmt.setString(2, username);
            int row=pstmt.executeUpdate();//rows affected
            System.out.println(row+" row affected");
            con.close(); 
            System.out.println("balance updated successfully");
        }catch(Exception e){ 
            System.err.println(e);
        }
    }
    public void printBalance(){
        if(username==null){
            System.out.println("User doesnt exist\n Check your username and password");
            return;
        }
        System.out.printf(username+"'s balance :"+"%.1f",getBalance());
        System.out.println();
    }
    
    public void printSharesHeld(){
        if(username==null){
            System.out.println("User doesnt exist\n Check your username and password");
            return;
        }
        String sql="select tickerid,sum(amount) as amount from Transaction where username=? group by username,tickerid";
        Connection con=null;
        try{ 
            con = DriverManager.getConnection(url,user,pass); 
            PreparedStatement pstmt = con.prepareStatement(sql);
            pstmt.setString(1, username);
            ResultSet rs=pstmt.executeQuery();
            while(rs.next()){
                System.out.println(rs.getString("tickerid")+" : "+rs.getString("amount"));
            }
            con.close(); 
        } 
        catch(Exception e){
            e.printStackTrace();
        }
    }
    
    public void chkCurrent(String symbol) throws Exception
    {
        if(!chkSymbol(symbol)){
            System.out.println("Check symbol again");
            return;
        }
        String sURL="https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol="+symbol+"&apikey=HWRLWXA50QHIR207";
        URL url=new URL(sURL);
        URLConnection request=url.openConnection();
        request.connect();
        
        Object obj=new JSONParser().parse(new InputStreamReader((InputStream) request.getContent()));
        JSONObject jobj=(JSONObject)obj;
        JSONObject info=(JSONObject)jobj.get("Global Quote");
        
        System.out.println("01. symbol: "+info.get("01. symbol"));
        System.out.println("02. open: "+info.get("02. open"));
        System.out.println("03. high: "+info.get("03. high"));
        System.out.println("04. low: "+info.get("04. low"));
        System.out.println("05. price: "+info.get("05. price"));
        System.out.println("06. volume: "+info.get("06. volume"));
        System.out.println("07. latest trading day: "+info.get("07. latest trading day"));
        System.out.println("08. previous close: "+info.get("08. previous close"));
        System.out.println("09. change: "+info.get("09. change"));
        System.out.println("10. change percent: "+info.get("10. change percent"));
    }
    
    private boolean chkSymbol(String symbol){
        symbol=symbol.toLowerCase();
        if(!shares.contains(symbol)){
            return false;
        }
        return true;
    }
    private String sha1(String input){//https://www.geeksforgeeks.org/sha-1-hash-in-java/
        try { 
            // getInstance() method is called with algorithm SHA-1 
            MessageDigest md = MessageDigest.getInstance("SHA-1"); 
  
            // digest() method is called 
            // to calculate message digest of the input string 
            // returned as array of byte 
            byte[] messageDigest = md.digest(input.getBytes()); 
  
            // Convert byte array into signum representation 
            BigInteger no = new BigInteger(1, messageDigest); 
  
            // Convert message digest into hex value 
            String hashtext = no.toString(16); 
  
            // Add preceding 0s to make it 32 bit 
            while (hashtext.length() < 32) { 
                hashtext = "0" + hashtext; 
            } 
  
            // return the HashText 
            return hashtext; 
        } 
  
        // For specifying wrong message digest algorithms 
        catch (NoSuchAlgorithmException e) { 
            throw new RuntimeException(e); 
        } 
    }
}
