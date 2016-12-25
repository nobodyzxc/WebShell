for $dir in `ls ..`
do
    if [ -d ../$dir ];then
        setfacl -m u:nobody:rwx ../$dir
        if [ $? != 0 ];then
            chmod 707 ../$dir
        fi
    fi
done
