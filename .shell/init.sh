for dir in `ls -a ..`
do
    if [ -d ../$dir ] && [ "$dir" != ".." ] ;then
        setfacl -m u:nobody:rwx ../$dir
        if [ $? != 0 ];then
            chmod 707 ../$dir
            if [ $? == 0 ];then
                echo 'chmod ok'
            else
                echo "chmod $dir failed"
            fi
        else
            echo "$dir ACL setted"
        fi
    fi
done
