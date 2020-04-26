package group.dny.api.utils;

import java.io.File;
import java.io.FileInputStream;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;

public class Resource {
	private static Resource loader = new Resource();
    private static Map<String,Properties> loaderMap = new HashMap<String, Properties>();  
  
    private Resource() {
    }  
  
    public static Resource getInstance() {
        return loader;  
    }  
      
    public Properties getPropFromProperties(String fileName) throws Exception {  
          
        Properties prop = loaderMap.get(fileName);  
        if (prop != null) {  
            return prop;  
        }  
        String filePath = null;  
        String configPath = System.getProperty("configurePath");  
  
        if (configPath == null) {  
            filePath = this.getClass().getClassLoader().getResource(fileName).getPath();  
        } else {  
            filePath = configPath + "/" + fileName;  
        }  
        prop = new Properties();  
        prop.load(new FileInputStream(new File(filePath)));  
  
        loaderMap.put(fileName, prop);  
        return prop;  
    }  
}
