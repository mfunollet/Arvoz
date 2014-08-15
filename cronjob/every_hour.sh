###########################################################################################
#
# 0 * * * * sh /home/powerdt/public_html/cronjob/every_hour.sh
###########################################################################################

wget -q --spider --content-disposition -O /dev/null 'http://powerdt.in/products/getproductsdetails'