const AWS = require('aws-sdk');
const S3  = new AWS.S3();

let total = 0;

exports.handler = async (event) => {
    const now = new Date();
    const d = new Date();
    d.setMonth(d.getMonth() - 3);
    
    for (; d <= now; d.setDate(d.getDate() + 1)) {
      const dateString = d.getUTCFullYear() + "/" +
                        ("0" + (d.getUTCMonth()+1)).slice(-2) + "/" +
                        ("0" + d.getUTCDate()).slice(-2);
      const keyS3 = `rotate-table/stt-don-tiep/${dateString}/data.json`;
      var params = {Bucket: 'hospital-79488' , Key: keyS3};
      
      var result =  await selectFromS3Case1(params);
      
      if (result) {
        result.Payload.on('data', await function(event) {
          if (event.Records) {
            // THIS IS OUR RESULT
            let buffer = event.Records.Payload;
            let data = JSON.parse(buffer.toString());
            total += data.total;
          }
        });
      }
    }
    console.log('total',total);
};

const selectFromS3Case2 =  async (params) => {
  try {
    let output2 = await S3.selectObjectContent({
        Bucket: params.Bucket,
        Key: params.Key,
        ExpressionType: 'SQL',
        Expression: `SELECT  
        sum(case when s.loai_stt = 'A' then 1 else 0 end) ACount,
        sum(case when s.loai_stt = 'C' then 1 else 0 end) CCount
        FROM S3Object[*][*] s`,
        InputSerialization: {
          JSON: {
            Type: 'DOCUMENT'
          }
        },
        OutputSerialization: {
          JSON: {
            RecordDelimiter: '\n'
          }
        }
      })
    .promise()
    return output2;
  } catch (ex) {
    return null;
  }  
}

const selectFromS3Case1 =  async (params) => {
  try {
    let output2 = await S3.selectObjectContent({
        Bucket: params.Bucket,
        Key: params.Key,
        ExpressionType: 'SQL',
        Expression: `SELECT COUNT(*) total 
        FROM S3Object[*][*] s WHERE s.loai_stt = 'A'`,
        InputSerialization: {
          JSON: {
            Type: 'DOCUMENT'
          }
        },
        OutputSerialization: {
          JSON: {
            RecordDelimiter: '\n'
          }
        }
      })
    .promise()
    return output2;
  } catch (ex) {
    return null;
  }  
}
