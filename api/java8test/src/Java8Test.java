public class Java8Test {
    public static void main(String args[]) {
//        List<String> names1 = new ArrayList<String>();
//        names1.add("Google ");
//        names1.add("Runoob ");
//        names1.add("Taobao ");
//        names1.add("Baidu ");
//        names1.add("Sina ");
//
//        List<String> names2 = new ArrayList<String>();
//        names2.add("Google ");
//        names2.add("Runoob ");
//        names2.add("Taobao ");
//        names2.add("Baidu ");
//        names2.add("Sina ");
//
//        Java8Test tester = new Java8Test();
//        System.out.println("使用 Java 7 语法: ");
//
//        tester.sortUsingJava7(names1);
//        System.out.println(names1);
//        System.out.println("使用 Java 8 语法: ");
//
//        tester.sortUsingJava8(names2);
//        System.out.println(names2);

        Java8Test tester = new Java8Test();

        MathOperation addition = (int a, int b) -> a + b;
        tester.operate(5, 10, addition);

        //不需要参数

    }

//    private void sortUsingJava7(List<String> names) {
//        Collections.sort(names, new Comparator<String>() {
//            @Override
//            public int compare(String s1, String s2) {
//                return s1.compareTo(s2);
//            }
//        });
//    }
//
//    private void sortUsingJava8(List<String> names) {
//        Collections.sort(names, (s1, s2) -> s1.compareTo(s2));
//    }

    interface MathOperation {
        int operation(int a, int b);
    }

    interface GreetingService {
        void sayMessage(String message);
    }

    private int operate(int a, int b, MathOperation mathOperation) {
        return mathOperation.operation(a, b);
    }
}
