###########################################################################################
# 
# 0 1 * * * sh /home/powerdt/public_html/cronjob/every_30_minutes.sh
###########################################################################################

wget -q --spider --content-disposition -O /dev/null "http://powerdt.in/crawlers/getproducts"