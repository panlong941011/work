package group.dny.api.dto;

public class CateoryDTO {
    private Integer lID;
    private String sName;
    private String GradeID;
    private Integer IProductNum;
    private Integer UpID;
    private String sPic;

    public Integer getlID() {
        return lID;
    }

    public void setlID(Integer lID) {
        this.lID = lID;
    }

    public String getsName() {
        return sName;
    }

    public void setsName(String sName) {
        this.sName = sName;
    }

    public String getGradeID() {
        return GradeID;
    }

    public void setGradeID(String gradeID) {
        GradeID = gradeID;
    }

    public Integer getIProductNum() {
        return IProductNum;
    }

    public void setIProductNum(Integer IProductNum) {
        this.IProductNum = IProductNum;
    }

    public Integer getUpID() {
        return UpID;
    }

    public void setUpID(Integer upID) {
        UpID = upID;
    }

    public String getsPic() {
        return sPic;
    }

    public void setsPic(String sPic) {
        this.sPic = sPic;
    }
}
